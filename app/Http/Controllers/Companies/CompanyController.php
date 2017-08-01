<?php
namespace App\Http\Controllers\Companies;

use App\Models\Companies\Company;
use App\Models\Resources\ResourceDefinition;
use App\Models\Scheduling\File;
use App\Models\Scheduling\PlanningConfiguration;
use App\Models\Scheduling\StatusConfiguration;
use App\Models\Scheduling\WorkingDayConfiguration;
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
use MongoDB\Exception\RuntimeException;
use App\Models\Applications\Application;

class CompanyController extends DatatrafficController
{
    //Nombre del modelo
    protected $modelo = 'App\Models\Companies\Company';

    //Titulo del reporte
    protected $excelTitle = 'Empresas';

    //Titulo del reporte
    protected $excelAttributes = '{ "name": 1, "nit": 1, "phone": 1, "status": 1, "address": 1, "country": { "country": 1 }, "city": { "asciiname": 1 }, "legalRepresentativeId": 1, "legalRepresentativeLastName": 1, "legalRepresentativeName": 1, "legalRepresentativePhone": 1, "licenses": { "application": { "name": 1, "modules": { "name": 1 } } },"file": { "delimiter": 1, "enclosure": 1, "encoding": 1, "fileName": 1, "filePath": 1, "formatDate": 1, "formatHour": 1 },"planningConfiguration": { "balance": 1, "minVehicule": 1, "minVisitsPerVehicle": 1, "shortestDistance": 1, "traffic": 1 }, "statusConfigurations": { "status": 1, "reasons": { "label": "Cerrado", "withPhoto": true } }, "workingDayConfiguration": { "endDiurnalTime": 1, "endExtraTime": 1, "endNightlyTime": 1, "intervalIgnition": 1, "maxHourWeekWorkingDay": 1, "maxHourWorkingDay": 1, "startDiurnalTime": 1, "startExtratime": 1, "startNightlyTime": 1 } }';

    public function store(Request $request)
    {
        $response = parent::store($request);
        $responseContent = $response->getContent();
        $dataContent = json_decode($responseContent,true);

        if(!$dataContent['error'])
        {
            foreach ($dataContent['data'] as $reference)
            {
                $company = Company::where('_id','=', new ObjectID($reference))->first();

                if($company) {
                    $this->createUserResourceDefinitionForCompany($company);
                    $this->createFileDefaultConfiguration($company);
                    $this->createPlanningDefaultConfiguration($company);
                    $this->createStatusDefaultConfiguration($company);
                    $this->createWorkingDayDefaultConfiguration($company);
                }
            }
        }

        return $response;
    }

    private function createUserResourceDefinitionForCompany($company)
    {
        $resourceDefinition = new ResourceDefinition();
        $resourceDefinition->id_company = ['$ref' => 'companies', '$id' => new ObjectID($company->_id)];
        $resourceDefinition->name = "Usuarios";
        $resourceDefinition->deviceDefinitions = [];
        $resourceDefinition->resourceDefinitions = [];
        $resourceDefinition->customAttributes = [
                                [ "xtype" => "textfield", "fieldLabel" => "name" ],
                                ["xtype" => "textfield", "fieldLabel" => "lastName" ]
                            ];
        $resourceDefinition->isSystem =  true;
        $resourceDefinition->save();
    }

    private function createFileDefaultConfiguration($company)
    {
        $file = new File();
        $file->delimiter = ",";
        $file->enclosure = "\"";
        $file->encoding = "UTF-8";
        $file->formatHour = "hh:mm:ss";
        $file->formatDate = "YYYY-MM-DD";
        $file->filePath = storage_path('app/files/');
        $file->fileName = "dummy.csv";

        $company->file()->save($file);
    }

    private function createPlanningDefaultConfiguration($company)
    {
        $planningConfiguration = new PlanningConfiguration();

        $planningConfiguration->minVehicule = 2;
        $planningConfiguration->minVisitsPerVehicle = 10;
        $planningConfiguration->traffic = "normal";
        $planningConfiguration->shortestDistance = false;
        $planningConfiguration->balance = false;
            
        $company->planningConfiguration()->save($planningConfiguration);
    }

    private function createStatusDefaultConfiguration($company)
    {
        $statuses = ["PENDIENTE","CHECKIN","CHECKOUT CON FORMULARIO","CHECKOUT SIN FORMULARIO", "CHECKOUT PENDIENTE"];

        foreach ($statuses as $status)
        {
            $statusConfiguration = new StatusConfiguration();
            $statusConfiguration->status = $status;
            $statusConfiguration->reasons = [];
            $company->statusConfigurations()->save($statusConfiguration);
        }
    }

    private function createWorkingDayDefaultConfiguration($company)
    {
        $workingDayConfiguration = new WorkingDayConfiguration();
        $workingDayConfiguration->startDiurnalTime = 21600;
        $workingDayConfiguration->endDiurnalTime = 79199;
        $workingDayConfiguration->startNightlyTime = 64800;
        $workingDayConfiguration->endNightlyTime = 21599;
        $workingDayConfiguration->maxHourWorkingDay = 28800;
        $workingDayConfiguration->maxHourWeekWorkingDay = 172800;
        $workingDayConfiguration->startExtratime = 79200;
        $workingDayConfiguration->endExtraTime = 64800;
        $workingDayConfiguration->intervalIgnition = 300;
        $company->workingDayConfiguration()->save($workingDayConfiguration);
    }


    /** Lista las licencias de una compañía
     * @param $strIdCompany
     * @return mixed
     */
    public function licensesList(Request $request, $strIdCompany)
    {
        $error = false;
        $msg = trans('general.MSG_OK');
        $total = 1;
        $intCode = 200;

        //Encontrar compañia
        $company = Company::find($strIdCompany);
        if(!$company)
        {
            $e = new ModelNotFoundException('Company');
            $e->setModel('Company');
            throw $e;
        }

        //Encontrar licencias
        $licenses = $company->licenses->toArray();

        $resultResponse = Util::outputJSONFormat($error, $msg, $total, $licenses);
        return response($resultResponse, $intCode);
    }

    /** Agrega una licencia al arreglo de licencias de una compañia
     * @param Request $request
     * @param $strIdCompany
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function licensesSave(Request $request, $strIdCompany)
    {
        $error = false;
        $msg = trans('general.MSG_OK');
        $total = 1;
        $intCode = 201;

        //Encontrar compañia
        $company = Company::find($strIdCompany);
        if(!$company)
        {
            $e = new ModelNotFoundException('Company');
            $e->setModel('Company');
            throw $e;
        }

        $json = $request->getContent();
        $arrayFromJson = json_decode($json, true);

        $idUserCreated = $this->dGetUserDBRefSession();

        $licenseController = new LicenseController();

        $result = $licenseController->storeModelFromArray($arrayFromJson, $idUserCreated, false);
        DB::collection('companies')->where('_id', $strIdCompany)->push("licenses", $result);

        $data = ["reference" =>$result['_id']->__toString()];
        $resultResponse = Util::outputJSONFormat($error, $msg, $total, $data);

        return response($resultResponse, $intCode);
    }

    /** Obtiene una licencia identificada con $strIdLicense de la compañia $strIdCompany
     * @param $strIdCompany
     * @param $strIdLicense
     * @return mixed
     */
    public function licensesGet($strIdCompany, $strIdLicense)
    {
        //Encontrar empresa
        $company = Company::find($strIdCompany);
        if(!$company)
        {
            $e = new ModelNotFoundException('Company');
            $e->setModel('Company');
            throw $e;
        }

        //Encontrar licencia
        $license = $company->licenses()->find($strIdLicense);
        if(!$license)
        {
            $e = new ModelNotFoundException('License');
            $e->setModel('License');
            throw $e;
        }

        return response()->json($license->toArray());
    }

    /** Actualiza una licencia específica de una compañía
     * @param $strIdCompany
     * @param $strIdLicense
     * @return mixed
     */
    public function licensesUpdate(Request $request, $strIdCompany, $strIdLicense)
    {
        //Encontrar empresa
        $company = Company::find($strIdCompany);
        if(!$company)
        {
            $e = new ModelNotFoundException('Company');
            $e->setModel('Company');
            throw $e;
        }

        //Encontrar licencia
        $license = $company->licenses()->find($strIdLicense);
        if(!$license)
        {
            $e = new ModelNotFoundException('License');
            $e->setModel('License');
            throw $e;
        }

        //Obtenemos el id del usuario creador
        $idUserUpdated = $this->dGetUserDBRefSession();
		
        
        
        //Informacion
        $json = $request->getContent();
        $arrayFromJson = json_decode($json, true);

        $arrayFromJson['_id'] = $strIdLicense;

        $arrRelationList = [];
        $arrRelationList["licenses"] = [$arrayFromJson];
        $id_app= $arrayFromJson['application']['_id'];
        $app = Application::where('_id',new ObjectID($id_app))->first();
        $application= [];
        $modulesUpdate=$arrayFromJson['application']['modules'];
        $indextoudate=0;
        if(!$app){
        	$e = new ModelNotFoundException('Application');
        	$e->setModel('Application');
        	throw $e;
        }
        $application['_id']= new ObjectID( $strIdLicense );
        $application['application']['name']= $app->name;
        $application['application']['_id'] = new ObjectID($id_app  );
        
        //bucar el indice a actualizar        	
        $licensesCompany = $company->licenses;
        foreach ($licensesCompany as $licensecompanyApp){
        	if(  strcmp( $licensecompanyApp['_id']."",$strIdLicense)==0 ){
        		break;
        	}
        	$indextoudate++;
        }
        	
        $modules= $app->modules;
        if( $modules ){
        	foreach ($modules as $module){
        		foreach ($modulesUpdate as $moduleUpdate){
        			if( strcmp($moduleUpdate['name'], $module['name']) == 0 ){
        				$application['application']['modules'][]=$module;
        				break;
        			}
        		}
        	}
        }
        //se actualizan los campos
        DB::collection('companies')->where('_id', $strIdCompany)->update(["licenses.$indextoudate"=> $application], ['upsert' => true]);
        //Preparando respuesta
        $error = false;
        $msg = trans('general.MSG_OK');
        $data = ["reference" => $strIdLicense];
        $total = 1;
        $intCode = 201;

        $resultResponse = Util::outputJSONFormat($error, $msg, $total, $data);

        return response($resultResponse, $intCode);
    }

    /** Elimina la licencia identificadacon $strIdLicense de la compañia $strIdCompany
     * @param $strIdCompany
     * @param $strIdLicense
     * @return mixed
     */
    public function licensesDelete($strIdCompany, $strIdLicense)
    {
        //Encontrar empresa
        $company = Company::find($strIdCompany);
        if(!$company)
        {
            $e = new ModelNotFoundException('Company');
            $e->setModel('Company');
            throw $e;
        }

        //Encontrar licencia
        $license = $company->licenses()->find($strIdLicense);
        if(!$license)
        {
            $e = new ModelNotFoundException('License');
            $e->setModel('License');
            throw $e;
        }

        //Eliminar licencia
        $license->delete();

        //Preparando respuesta
        $error = false;
        $msg = trans('general.MSG_OK');
        $data = ["reference" => $strIdLicense];
        $total = 1;
        $intCode = 200;

        //Respondemos
        $resultResponse = Util::outputJSONFormat($error, $msg, $total, $data);
        return response($resultResponse, $intCode);
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see \App\Http\Controllers\GenericMongo\DatatrafficController::beforeDeleted()
     */
    public function beforeDeleted( $id ){
    	
    	if(Util::$insUser){
    		$company = Util::$insUser->company;
    		if (  strcmp($company->_id.'', $id )==0  ){
    			throw new RuntimeException('error.can_not_deleted_the_same_company');
    		}
    	} else {
    		throw new RuntimeException('error.session_request');
    	}
    }
    /**
     * 
     * {@inheritDoc}
     * @see \App\Http\Controllers\GenericMongo\DatatrafficController::afterDeleted()
     */
    public function afterDeleted( \Jenssegers\Mongodb\Eloquent\Model $model ){
    
    }
}
