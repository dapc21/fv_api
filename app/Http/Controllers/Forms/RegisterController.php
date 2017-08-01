<?php
namespace App\Http\Controllers\Forms;

use App\Http\Controllers\GenericMongo\ControllerTraitCompanyCustomAttribute;
use App\Models\Forms\Form;
use App\Models\Companies\Company;
use App\Models\Forms\Register;
use App\Models\Planning\Task;
use Carbon\Carbon;
use App\datatraffic\lib\Util;
use App\datatraffic\lib\Configuration;
use App\datatraffic\lib\ErrorMessages;
use App\datatraffic\dao\Generic;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Auth;
use App\Http\Controllers\GenericMongo\DatatrafficController;
use MongoDB\BSON\ObjectID;

class RegisterController extends DatatrafficController
{
    use ControllerTraitCompanyCustomAttribute;
    
    //Nombre del modelo
    public $modelo = 'App\Models\Forms\Register';

    public function store(Request $request) {
        if($request->isJson()){
            $jsonData = $request->getContent();
        }
        else {
            if (!$request->has('data'))
            {
                throw new \Exception("No se especifico el registro");
            }
            $data = utf8_encode($request->get('data'));
            $jsonData = json_decode($data,true);
        }

        if(!array_key_exists('id_task',$jsonData)) {
            throw new \Exception("No se especifico id_task");
        }
        $strIdTask = $jsonData['id_task'];

        if(!array_key_exists('id_form',$jsonData)) {
            throw new \Exception("No se especifico id_form");
        }
        $strIdForm = $jsonData['id_form'];

        if(!array_key_exists('localId',$jsonData)) {
            throw new \Exception("No se especifico localId");
        }
        $strIdRegister = $jsonData['localId'];

        return $this->saveFromArray($jsonData, $strIdTask, $strIdForm, $strIdRegister, false);
    }

    public function saveFromRequest(Request $request, $strIdTask, $strIdForm, $strIdRegister)
    {
        if($request->isJson()){
            $jsonData = $request->getContent();
        }
        else {
            if (!$request->has('data'))
            {
                throw new \Exception("No se especifico el registro");
            }
            $data = utf8_encode($request->get('data'));
            $jsonData = json_decode($data,true);
        }

        $id = $this->saveFromArray($jsonData, $strIdTask, $strIdForm, $strIdRegister, false);
        $path = public_path('images');
        Util::saveFiles($request,$path);

        $error = false;
        $msg = trans('general.MSG_OK');
        $data = ["reference" => $id];
        $total = 1;
        $intCode = 201;
        $view = [];
        $result = Util::outputJSONFormat($error, $msg, $total, $data, $view);

        return response($result, $intCode);
    }

    public function saveFromArray($jsonData, $strIdTask, $strIdForm, $strIdRegister)
    {
        $idUserCreated = $this->dGetUserDBRefSession();

        $fieldLabels = $this->getFieldLabelsFromForm($strIdForm);
        $notUpdateableFields = $this->getNotUpdateableFieldsFromForm($strIdForm);

        $register = Register::where('id_task',['$ref' => 'tasks', '$id' =>new ObjectID($strIdTask)])
            ->where('id_form',['$ref' => 'forms', '$id' =>new ObjectID($strIdForm)])
            ->where('localId',$strIdRegister)
            ->first();

        if(!$register){
            $arrayFromJson = [];
            $arrayFromJson['dataMovil'] = $jsonData;

            if(array_key_exists('questions',$jsonData)){
                $originalQuestions = $jsonData['questions'];
            }
            else {
                $originalQuestions = [];
            }
            $dataWeb = $this->movilToWeb("",$originalQuestions);
            $arrayFromJson['dataWeb'] = $dataWeb;

            $dataElastic = $this->movilToElastic($fieldLabels, $originalQuestions);
            $arrayFromJson['dataElastic'] = $dataElastic;

            $task = Task::where('_id',new ObjectId($strIdTask))->first();

            $arrayFromJson['id_formType'] = $strIdForm;
            if($task){
                $arrayFromJson['arrival_time'] = $task->arrival_time->toDateTimeString();
                $arrayFromJson['id_company'] = $task->company->_id;
                $arrayFromJson['id_resourceInstance'] = (string) $task->id_resourceInstance['$id'];
                $arrayFromJson['login'] = $task->resourceInstance->login;
            }
            else {
                $arrayFromJson['arrival_time'] = Carbon::now('GMT-0')->toDateTimeString();
                $arrayFromJson['id_company'] = Util::$insUser->id_company;
                $arrayFromJson['id_resourceInstance'] = (string)$idUserCreated['$id'];
                $arrayFromJson['login'] = Util::$insUser->login;
            }
            $arrayFromJson['id_task'] = $strIdTask;
            $arrayFromJson['id_form'] = $strIdForm;
            $arrayFromJson['localId'] = $strIdRegister;

            $insModelInsert = $this->storeModelFromArray($arrayFromJson, $idUserCreated, true);

            $relatedModel = new $this->modelo();
            $primaryKeyName = $relatedModel->getPrimaryKey();
            $id = $insModelInsert[$primaryKeyName]->__toString();
        }
        else {
            //Valores originales
            $originalDataWeb = $register->dataWeb;
            if(!is_array($originalDataWeb)){
                $originalDataWeb = [];
            }
            $originalDataElastic = $register->dataElastic;
            if(!is_array($originalDataElastic)){
                $originalDataElastic = [];
            }
            $originalDataMovil = $register->dataMovil;
            if(array_key_exists('questions',$originalDataMovil)){
                $originalQuestions = $originalDataMovil['questions'];
            }
            else {
                $originalQuestions = [];
            }
            if(array_key_exists('showGrid',$originalDataMovil)){
                $originalShowGrid = $originalDataMovil['showGrid'];
            }
            else {
                $originalShowGrid = "";
            }
            if(array_key_exists('sectionsStatus',$originalDataMovil)){
                $originalSectionsStatus = $originalDataMovil['sectionsStatus'];
            }
            else {
                $originalSectionsStatus = "";
            }

            //Nuevos valores
            $arrData = [];
            if(array_key_exists('questions',$jsonData)){
                //Hacer merge con la informacion original
                $arrData['dataMovil']['questions'] = array_merge($originalQuestions,$jsonData['questions']);
            }
            else {
                $arrData['dataMovil']['questions'] = $originalQuestions;
            }
            if(array_key_exists('showGrid',$jsonData)){
                $arrData['dataMovil']['showGrid'] = $jsonData['showGrid'];
            }
            else {
                $arrData['dataMovil']['showGrid'] = $originalShowGrid;
            }
            if(array_key_exists('sectionsStatus',$jsonData)){
                $arrData['dataMovil']['sectionsStatus'] = $jsonData['sectionsStatus'];
            }
            else {
                $arrData['dataMovil']['sectionsStatus'] = $originalSectionsStatus;
            }

            //DataWeb y DataElastic
            $newDataWeb = $this->movilToWeb("",$arrData['dataMovil']['questions']);
            $newDataElastic = $this->movilToElastic($fieldLabels, $arrData['dataMovil']['questions']);
            //Eliminar campos que son actualizados por otros metodos, por ejemplo desde carga de imagenes
            foreach ($notUpdateableFields as $key => $notUpdateableField){
                unset($newDataWeb[$key]);
                unset($newDataElastic[$key]);
            }
            $arrData['dataWeb'] = array_merge($originalDataWeb,$newDataWeb);
            $arrData['dataElastic'] = array_merge($originalDataElastic,$newDataElastic);

            //Arreglo de sincronizacion
            $arrRelationsSynchronize = [];

            $this->updateObject($register, $arrData, $arrRelationsSynchronize, $idUserCreated);

            $id = $register->getIdPrimaryKey();
        }

        return $id;
    }

    public function movilToWeb($cid, $element) {
        $result = [];

        if(array_key_exists('data',$element))
        {
            if(!is_array($element['data']))
            {
				if(array_key_exists('cid',$element))
				{
					$key = $element['cid'];
				}
				else{
					$key = $cid;
				}
				
                $result[$key] = $element['data'];
            }
            else
            {
				if(array_key_exists('cid',$element))
				{
					$key = $element['cid'];
				}
				else{
					$key = $cid;
				}
				
                $result[$key] = [];
                foreach($element['data'] as $subkey => $subElement)
                {
                    array_push($result[$key], $this->movilToWeb($subkey, $subElement));
                }
            }
        }
        else
        {
            foreach($element as $key => $subElement)
            {
                if($key != 'calculation')
                {
                    $result = array_merge($result, $this->movilToWeb($key, $subElement));
                }
            }
        }
        return $result;
    }

    public function movilToElastic(&$fieldLabels,$element) {
        $result = [];
        /*if(array_key_exists('data',$element))
        {
            $key = trans("deleted_question")." ".$element['cid'];
            if(array_key_exists($element['cid'],$fieldLabels)){
                $key = $fieldLabels[$element['cid']];
            }

            if(!is_array($element['data']))
            {
                $result[$key] = $element['data'];
            }
            else
            {
                $result[$key] = [];
                foreach($element['data'] as $subElement)
                {
                    array_push($result[$key], $this->movilToElastic($fieldLabels,$subElement));
                }
            }
        }
        else
        {
            foreach($element as $key => $subElement)
            {
                if($key != 'calculation')
                {
                    $result = array_merge($result, $this->movilToElastic($fieldLabels,$subElement));
                }
            }
        }*/
        return $result;
    }

    private function getFieldLabelsFromForm($idForm){
        $labels = [];
        $sections = DB::collection('sections')->where('id_form','=',$idForm)->project(["questions.cid"=>1,"questions.configuration.fieldLabel"=>1])->get();
        foreach ($sections as $section) {
            foreach ($section['questions'] as $question) {
                $labels[$question['cid']] = $question['configuration']['fieldLabel'];
            }
        }

        return $labels;
    }

    private function getNotUpdateableFieldsFromForm($idForm){
        $labels = [];
        $sections = DB::collection('sections')->whereIn('questions.xtype',['filefield','photofield','signaturefield','recordvideofield','recordvoicefield',])->project(["questions.cid"=>1,"questions.configuration.fieldLabel"=>1])->get();
        foreach ($sections as $section) {
            foreach ($section['questions'] as $question) {
                $labels[$question['cid']] = $question['configuration']['fieldLabel'];
            }
        }

        return $labels;
    }
}
