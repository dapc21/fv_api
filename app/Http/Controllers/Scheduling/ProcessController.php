<?php
namespace App\Http\Controllers\Scheduling;

use App\Exceptions\MissingRequestParameter;
use App\Http\Controllers\GenericMongo\ControllerTraitCompanyCustomAttribute;
use App\Models\Forms\Campaign;
use App\Models\Forms\Form;
use App\Models\Forms\Section;
use App\Models\Companies\Company;
use App\Models\Forms\Register;
use App\Models\Scheduling\Step;
use Carbon\Carbon;
use App\datatraffic\lib\Util;
use App\datatraffic\lib\Configuration;
use App\datatraffic\lib\ErrorMessages;
use App\datatraffic\dao\Generic;
use Hamcrest\AssertionError;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Auth;
use App\Http\Controllers\GenericMongo\DatatrafficController;
use \COM;
use MongoDB\BSON\ObjectID;
use App\Models\Scheduling\Process;
use App\Models\Resources\ResourceInstance;
use MongoDB\Exception\RuntimeException;

class ProcessController extends DatatrafficController
{
    use ControllerTraitCompanyCustomAttribute;

    //Nombre del modelo
    protected $modelo = 'App\Models\Scheduling\Process';

    public function upload(Request $request) {

        //Recuperar json
        if (!$request->has('data'))
        {
            throw new MissingRequestParameter('No se especifico el parametro data');
        }
        $json = utf8_encode($request->get('data'));
        $arrayFromJson = json_decode($json, true);

        //Completar informacion por defecto de la empresa
        $id_company = $arrayFromJson['id_company'];
        $company = Company::where('_id','=',new ObjectID($id_company))->first();
        if(!$company) {
            throw new \Exception('Empresa no encontrada');
        }
        //validar que exista por lo menos un recurso a asignar
        $resourceDefs =[];
        $resourceExclude = [];
        $resourceGroups =[];
        if(array_key_exists('resourceDefinitions', $arrayFromJson)){
        	foreach ($arrayFromJson['resourceDefinitions'] as  $strIdResDef ){
        		$resourceDefs[]=['$ref' => 'resourceDefinitions', '$id' => new \MongoDB\BSON\ObjectId( $strIdResDef ) ];
        	}
        	
        }
        if(array_key_exists('resourceGroups', $arrayFromJson)){
        	foreach ($arrayFromJson['resourceGroups'] as  $strIdResGr ){
        		$resourceGroups[]=['$ref' => 'resourceGroups', '$id' => new \MongoDB\BSON\ObjectId( $strIdResGr ) ];
        	}
        }
        if(array_key_exists('resourceInstances', $arrayFromJson)){
        	foreach ($arrayFromJson['resourceInstances'] as  $strIdResIns ){
        		$resourceExclude[]=new \MongoDB\BSON\ObjectId( $strIdResIns );
        	}
        }
        
        $queryResource = ResourceInstance::where('id_company',$company->getDBRef());
        if( !empty($resourceDefs)){
        	$queryResource = $queryResource->whereIn('id_resourceDefinition',$resourceDefs);
        }
        if( !empty($resourceGroups)){
        	$queryResource = $queryResource->whereIn('resourceGroups',$resourceGroups);
        }
        if( !empty($resourceDefs)){
        	$queryResource = $queryResource->whereNotIn('_id',$resourceExclude);
        }

        // se valida que por lo menos exista un recurso a programar
        if( $queryResource->count() <= 0 ){
        	throw new RuntimeException('error.no_resource_for_planning');
        }
        
        //File
        $defaultFileConfig = $company->file;
        $arrayFromJson['file'] = $defaultFileConfig->toArray();

        //PlanningConfiguration
        $defaultPlanningConfig = $company->planningConfiguration;
        $arrayFromJson['planningConfiguration'] = $defaultPlanningConfig->toArray();

        //StatusConfigurations
        $defaultStatusConfig = $company->statusConfigurations;
        $arrayFromJson['statusConfigurations'] = $defaultStatusConfig->toArray();

        //StartEndLocations
        $defaultStartEndLocationsConfig = $company->startEndLocations;
        $arrayFromJson['startEndLocations'] = $defaultStartEndLocationsConfig->toArray();

        //Guardar Archivos
        if (!$request->hasFile('file'))
        {
            throw new MissingRequestParameter('No se especifico el parametro file');
        }
        $path = storage_path('app/files/');
        $savedFiles = Util::saveFiles($request, $path);
        foreach ($savedFiles as $saveFile){
            $arrayFromJson['file']['filePath'] =  $saveFile['filePath'];
            $arrayFromJson['file']['fileName'] =  $saveFile['fileName'];
        }

        //Paso VisitsStep en WAITING
        $arrayFromJson['actualStep']['_class'] = "VisitsStep";
        $arrayFromJson['actualStep']['name'] = "VisitsStep";
        $arrayFromJson['actualStep']['status'] = "WAITING";
        $arrayFromJson['actualStep']['totalLines'] = 0;
        $arrayFromJson['actualStep']['totalProcessed'] = 0;
        $arrayFromJson['actualStep']['totalError'] = 0;
        $arrayFromJson['actualStep']['totalOK ']= 0;

        //Usuario actual
        $idUserCreated = $this->dGetUserDBRefSession();

        //Hacer el insert
        $insModelInsert = $this->storeModelFromArray($arrayFromJson, $idUserCreated, true);

        $relatedModel = new $this->modelo();
        $primaryKeyName = $relatedModel->getPrimaryKey();
        $id = $insModelInsert[$primaryKeyName]->__toString();

        $error = false;
        $msg = trans('general.MSG_OK');
        $data = ["reference" => $id];
        $total = 1;
        $intCode = 201;
        $view = [];
        $result = Util::outputJSONFormat($error, $msg, $total, $data, $view);

        $scriptPath = base_path('scripts');
        $cmd = "sh ".$scriptPath."/call_scheduler.sh ".$id;
        exec($cmd . " > /dev/null &");

        return response($result, $intCode);
    }

    public function schedule(Request $request, $idProcess) {
        $process = Process::where('_id','=',new ObjectID($idProcess))->first();
		if(!$process) {
            throw new ModelNotFoundException('Process not found');
        }
        $actualStep = new Step();
        $actualStep->_class = "PlanningStep";
        $actualStep->name = "PlanningStep";
        $actualStep->status = "WAITING";
        $process->actualStep()->save($actualStep);

		//Ejecutar el proceso que simula el procesamiento
		$scriptPath = base_path('scripts');
		$cmd = "sh ".$scriptPath."/call_scheduler.sh ".$idProcess;
		exec($cmd . " > /dev/null &");

        $error = false;
        $msg = trans('general.MSG_OK');
        $data = ["reference" => $idProcess];
        $total = 1;
        $intCode = 200;
        $view = [];

        $result = Util::outputJSONFormat($error, $msg, $total, $data, $view);

        return response($result, $intCode);
    }

    public function accept(Request $request, $idProcess) {
        $process = Process::where('_id','=',new ObjectID($idProcess))->first();
		if(!$process) {
            throw new ModelNotFoundException('Process not found');
        }
        $actualStep = new Step();
        $actualStep->_class = "TaskGeneratingStep";
        $actualStep->name = "TaskGeneratingStep";
        $actualStep->status = "WAITING";
        $process->actualStep()->save($actualStep);

        //Ejecutar el proceso que simula el procesamiento
		$scriptPath = base_path('scripts');
		$cmd = "sh ".$scriptPath."/call_scheduler.sh ".$idProcess;
		exec($cmd . " > /dev/null &");

        $error = false;
        $msg = trans('general.MSG_OK');
        $data = ["reference" => $idProcess];
        $total = 1;
        $intCode = 200;
        $view = [];

        $result = Util::outputJSONFormat($error, $msg, $total, $data, $view);

        return response($result, $intCode);
    }
}