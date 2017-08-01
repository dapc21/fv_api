<?php
namespace App\Http\Controllers\Forms;

use App\Http\Controllers\GenericMongo\ControllerTraitCompanyCustomAttribute;
use App\Models\Forms\Campaign;
use App\Models\Forms\Form;
use App\Models\Forms\Section;
use App\Models\Companies\Company;
use App\Models\Forms\Register;
use Carbon\Carbon;
use App\datatraffic\lib\Util;
use App\datatraffic\lib\Configuration;
use App\datatraffic\lib\ErrorMessages;
use App\datatraffic\dao\Generic;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Auth;
use App\Http\Controllers\GenericMongo\DatatrafficController;

class FormController extends DatatrafficController
{
    use ControllerTraitCompanyCustomAttribute;

    //Nombre del modelo
    protected $modelo = 'App\Models\Forms\Form';

    //Titulo del reporte
    protected $excelTitle = 'general.export_titles.forms';

    //Titulo del reporte
    protected $excelAttributes = '{"name":1,"description":1,"company":{"name":1},"sections":{"name":1, "questions":{"xtype":1, "configuration":{"fieldLabel":1, "allowBlank":1,"hidden":1}}}}';

    public function index(Request $request) {
        //DB::connection()->enableQueryLog();

        $sorts = $request->has('sort') ? json_decode($request->get('sort'), true) : [];
        $filters = $request->has('filters') ? json_decode($request->get('filters'), true) : [];
        $relations = $request->has('relations') ? json_decode($request->get('relations')) : [];
        $view = $request->has('view') ? json_decode($request->get('view')) : [];

        $page = $request->has('page') ? $request->get('page') : 1;
        $limit = $request->has('limit') ? $request->get('limit') : 15;

        $object = new Form;
        $query = $object->withoutGlobalScope(new SoftDeletingScope());
        $query->with(['sections'=>function($queryRelation){
            $queryRelation->orderBy('order', 'asc');
        }]);

        if (!empty($filters)) {
            $query = $this->filters($object, $query, $filters);
        }
        else {
            $usedTraits = class_uses($object);
            $softDeleteTrait = 'Jenssegers\Mongodb\Eloquent\SoftDeletes';

            if (in_array($softDeleteTrait, $usedTraits)) {
                $scope = new SoftDeletingScope();
                $scope->apply($query, $object);
            }
        }

        if (!empty($relations)) {
            $query = $this->relations($object, $query, $relations);
        }

        if (!empty($sorts)) {
            $query = $this->orders($object, $query, $sorts);
        }
        $result = $query->get();

        $error = false;
        $msg = trans('general.MSG_OK');
        $data = $result->toArray();
        $total = 1;
        $intCode = 200;

        $result = Util::outputJSONFormat($error, $msg, $total, $data, $view);

        //dump(DB::connection()->getQueryLog());

        return response($result, $intCode);
    }

    public function getSections(Request $request, $idForm)
    {
        $form = Form::where('id_form','=',$idForm)->get();

        if(!$form)
        {
            $e = new ModelNotFoundException();
            $e->setModel('Form');
            throw $e;
        }

        //Obtenemos sólo el query via GET
        $arrDataQuery = $request->all();

        //Obtenemos todos los datos soportados
        $limit     = empty($arrDataQuery['limit'])? 15 : (int)$arrDataQuery['limit'];

        $sections = Section::where('id_form','=',(int)$idForm)->get();
        $data = $sections->toArray();

        $error = false;
        $msg = trans('general.MSG_OK');
        $total = 1;
        $intCode = 200;
        $view = null;

        $result = Util::outputJSONFormat($error, $msg, $total, $data, $view);

        return response($result, $intCode);

    }

    public function storeRegister(Request $request, $idForm){

        //Obtener el parametero register
        if (!$request->has('register'))
        {
            throw new \Exception("No se especifico el registro");
        }
        $data = $request->get('register');
        $jsonData = json_decode($data,true);
        $dataMovil = $jsonData['data'];

        //Obtener formulario
        $form = Form::where('id_form','=',(int)$idForm)->first();
        if(!$form)
        {
            $e = new ModelNotFoundException();
            $e->setModel('Form');
            throw $e;
        }

        //Obtener campaña
        $id_campaign = $form->id_campaign;
        $campaign = Campaign::where('id_campaign', '=', (int)$id_campaign)->first();
        if(!$campaign)
        {
            $e = new ModelNotFoundException();
            $e->setModel('Campaign');
            throw $e;
        }

        //Obtener tarea
        $task = $campaign->task;
        if(!$task)
        {
            $e = new ModelNotFoundException();
            $e->setModel('Task');
            throw $e;
        }

        //Construir el formato que usa web
        $dataMovilUnificado = [];
        foreach ($dataMovil as $arrayForm)
        {
            foreach ($arrayForm as $arraySection)
            {
                $dataMovilUnificado = array_merge($dataMovilUnificado,$arraySection);
            }
        }
        $dataWeb = $dataMovilUnificado;

        //Estado de la tarea
        $newTaskStatus = $this->getTaskStatusFromRegister($task->status, $dataWeb);
        $task->status = $newTaskStatus;
        $task->save();

        //Guardar el registro
        $id_form_register = (int)round(microtime(true));
        $register = new Register();
        $register->id_form = $idForm;
        $register->id_form_register = $id_form_register;
        $register->dataMovil = $dataMovil;
        $register->dataWeb = $dataWeb;
        $task->register()->save($register);

        //Actualizar actual
        $nuevosdatos["tasks.$.status"] = $newTaskStatus;
        $nuevosdatos["tasks.$.register"] = $register->toArray();
        $resource = Util::$insUser;
        DB::collection('actual')->where('resource._id',$resource->_id)->where("tasks._id",$task->_id)->update($nuevosdatos);

        $error = false;
        $msg = trans('general.OK');
        $total = 1;
        $data = [["reference" => $id_form_register]];
        $view = null;
        $result = Util::outputJSONFormat($error, $msg, $total, $data, $view);
        return $result;
    }

    public function updateRegister(Request $request, $idForm, $idRegister){

        //Obtener el parametero register
        if (!$request->has('register'))
        {
            throw new \Exception("No se especifico el registro");
        }
        $data = $request->get('register');
        $jsonData = json_decode($data,true);
        $dataMovil = $jsonData['data'];

        //Obtener formulario
        $form = Form::where('id_form','=',(int)$idForm)->first();
        if(!$form)
        {
            $e = new ModelNotFoundException();
            $e->setModel('Form');
            throw $e;
        }

        //Obtener campaña
        $id_campaign = $form->id_campaign;
        $campaign = Campaign::where('id_campaign', '=', (int)$id_campaign)->first();
        if(!$campaign)
        {
            $e = new ModelNotFoundException();
            $e->setModel('Campaign');
            throw $e;
        }

        //Obtener tarea
        $task = $campaign->task;
        if(!$task)
        {
            $e = new ModelNotFoundException();
            $e->setModel('Task');
            throw $e;
        }

        //Construir el formato que usa web
        $dataMovilUnificado = [];
        foreach ($dataMovil as $arrayForm)
        {
            foreach ($arrayForm as $arraySection)
            {
                $dataMovilUnificado = array_merge($dataMovilUnificado,$arraySection);
            }
        }
        $dataWeb = $dataMovilUnificado;

        //Estado de la tarea
        $newTaskStatus = $this->getTaskStatusFromRegister($task->status, $dataWeb);
        $task->status = $newTaskStatus;
        $task->save();

        //Guardar el registro
        $register = new Register();
        $register->id_form = $idForm;
        $register->id_form_register = $idRegister;
        $register->dataMovil = $dataMovil;
        $register->dataWeb = $dataWeb;
        $task->register()->save($register);

        //Actualizar actual
        $nuevosdatos["tasks.$.status"] = $newTaskStatus;
        $nuevosdatos["tasks.$.register"] = $register->toArray();
        $resource = Util::$insUser;
        DB::collection('actual')->where('resource._id',$resource->_id)->where("tasks._id",$task->_id)->update($nuevosdatos);

        $error = false;
        $msg = trans('general.OK');
        $total = 1;
        $data = [["reference" => $idRegister]];
        $view = null;
        $result = Util::outputJSONFormat($error, $msg, $total, $data, $view);
        return $result;
    }

    public function getRegister(Request $request, $idForm){
        //Obtener formulario
        $form = Form::where('id_form','=',(int)$idForm)->first();
        if(!$form)
        {
            $e = new ModelNotFoundException();
            $e->setModel('Form');
            throw $e;
        }

        //Obtener campaña
        $id_campaign = $form->id_campaign;
        $campaign = Campaign::where('id_campaign', '=', (int)$id_campaign)->first();
        if(!$campaign)
        {
            $e = new ModelNotFoundException();
            $e->setModel('Campaign');
            throw $e;
        }

        //Obtener tarea
        $task = $campaign->task;
        if(!$task)
        {
            $e = new ModelNotFoundException();
            $e->setModel('Task');
            throw $e;
        }

        $data = [];
		$data['data'] = [];
        $register = $task->register;
        if($register)
        {
            $id_form_register = $register->id_form_register;
            $dataMovil = $register->dataMovil;
            $data['data'][] = ['id_form_register' => $id_form_register, 'data' => ['f'.$idForm => $dataMovil['f'.$idForm]]];
        }

		$arrayPagination["pagination"]["total"] = 1;
        $arrayPagination["pagination"]["per_page"] = 1;
		$arrayPagination["pagination"]["current_page"] = 1;
		$arrayPagination["pagination"]["last_page"] = 1;
		$arrayPagination["pagination"]["from"] = 1;
		$arrayPagination["pagination"]["to"] = 1;

        $arrayFinal = array_merge($arrayPagination, $data);
		
        $error = false;
        $msg = trans('general.OK');
        $total = 1;
        $view = null;
        $result = Util::outputJSONFormat($error, $msg, $total, $arrayFinal, $view);
        return $result;
    }

    public function getTaskStatusFromRegister($actualStatus, $registerData)
    {
        //C21 es la fecha de checkin
        //C24 es la fecha de checkout
        //C28 indica que finalizaron el formulario. Es true y false como strings.

        if(isset($registerData['c28'])){
            $c28 = $registerData['c28'];
        }
        else{
            $c28 = "false";
        }

        if(isset($registerData['c21'])){
            $c21 = $registerData['c21'];
        }
        else{
            $c21 = "";
        }

        if(isset($registerData['c24'])){
            $c24 = $registerData['c24'];
        }
        else{
            $c24 = "";
        }

        if($c28 === "true"){
            if(strlen($c24) > 0)
            {
                $newStatus = "CHECKOUT CON FORMULARIO";
            }
            else
            {
                $newStatus = $actualStatus;
            }
        }
        else{
            if(strlen($c24) > 0)
            {
                $newStatus = "CHECKOUT SIN FORMULARIO";
            }
            else if(strlen($c21) > 0)
            {
                $newStatus = "CHECKIN";
            }
            else
            {
                $newStatus = $actualStatus;
            }
        }

        return $newStatus;
    }

    public function show(Request $request, $strId)
    {
        $intCode = 200;

        $filters = ['and' => [['field' => '_id', 'comparison' => 'eq', 'value' => $strId]]];
        $relations = $request->has('relations') ? json_decode($request->get('relations')) : [];
        $view = $request->has('view') ? json_decode($request->get('view')) : [];

        $page = 1;
        $limit = -1;

        $object = new Form;
        $query = $object->withoutGlobalScope(new SoftDeletingScope());
        $query->with(['sections'=>function($queryRelation){
            $queryRelation->orderBy('order', 'asc');
        }]);

        if (!empty($filters)) {
            $query = $this->filters($object, $query, $filters);
        }
        else {
            $usedTraits = class_uses($object);
            $softDeleteTrait = 'Jenssegers\Mongodb\Eloquent\SoftDeletes';

            if (in_array($softDeleteTrait, $usedTraits)) {
                $scope = new SoftDeletingScope();
                $scope->apply($query, $object);
            }
        }

        if (!empty($relations)) {
            $query = $this->relations($object, $query, $relations);
        }

        if (!empty($sorts)) {
            $query = $this->orders($object, $query, $sorts);
        }
        
        $result = $this->paginate($object, $query, $page, $limit, $view);

        if($result->count() == 0)
        {
            $e = new ModelNotFoundException();
            $e->setModel(get_class($object));
            throw $e;
        }

        $data = $result->pop()->toArray();

        return response($data, $intCode);
    }
}
