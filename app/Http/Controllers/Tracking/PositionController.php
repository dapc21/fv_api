<?php

namespace App\Http\Controllers\Tracking;

use App\datatraffic\lib\Util;
use App\datatraffic\lib\UtilSpatial;
use App\Http\Controllers\Controller;
use App\Models\Icons\Icon;
use App\Models\Tracking\Actual;
use App\Models\Tracking\DeviceData;
use App\Models\Tracking\History;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDatetime;

class PositionController extends Controller
{
    //Nombre del modelo
    protected $modelo = 'App\Models\Tracking\Position';

    public function store(Request $request)
    {
        if($request->isJson()){
            $positions = $request->getContent();
        }
        else {
            $json = $request->getContent();
            $positions = json_decode($json, true);
        }

        return $this->storePosition([$positions],false);
    }

    public function storePosition($positions, $multiple)
    {
        $data = [];
        
        $actualUser = Util::$insUser;
        $actual = Actual::where('resourceInstance._id', '=', new ObjectID($actualUser->_id))->first();
        //$lastPosition = end($positions);
        foreach ($positions as $key => $position) {
        	
        	$isduplicate=false;
        	$updatetime = null;
        	//Actual 
        	$reporteReciente=false;
        	if (!$actual) {
        		$actual = new Actual();
        		$newUpdateTime = Carbon::createFromFormat('Y-m-d H:i:s', $position["updatetime"],'UTC');
        		$actual->_class = "co.com.datatraffic.fieldvision.tracking.Actual";
        		$actual = $this->createModel($actual, $position);
        		$actual->isVisible = true;
        		$actual->tsLastVisiblePosicion = $newUpdateTime;
        		$reporteReciente = true;
        	}
        	else {
        		$actualUpdateTime = $actual->updateTime;
        		$actualUpdateTime->setTimezone('UTC');
        		$updatetime=$actual->updateTime;
        		$newUpdateTime = Carbon::createFromFormat('Y-m-d H:i:s', $position["updatetime"], 'UTC');
        		
        		$isduplicate= $newUpdateTime->diffInSeconds($updatetime)==0;
        		$reporteReciente = $newUpdateTime->gt($actualUpdateTime);        		
        		//calcular la distancia y determinar si es visible 
        		$distance= UtilSpatial::haversineGreatCircleDistance($actual->latitude, $actual->longitude, $position["latitude"], $position["longitude"]);
        		
        		if( is_null( $actual->tsLastVisiblePosicion) || $distance > 20 ){
        			$actual->isVisible = true;
        			$actual->tsLastVisiblePosicion = $newUpdateTime;
        		}else{
        			//determinar si es visible por tiempo
        			if( $reporteReciente && $actualUpdateTime->diffInMinutes( $actual->tsLastVisiblePosicion ) >10 ){
        				$actual->isVisible = true;
        				$actual->tsLastVisiblePosicion = $newUpdateTime;
        			}else{
        				$actual->isVisible = false;
        				$actual->tsLastVisiblePosicion = $actual->tsLastVisiblePosicion;
        			}
        		}
        	}
        	//Inserta historial solo si la trama no se duplica con respecto a la anterior
        	if( $isduplicate ==false){
	        	if ($reporteReciente) {
	        		$actual = $this->updateModel($actual, $position);
	        	}
	        	//Historial
	            $history = new History();
	            $history->_class = "co.com.datatraffic.fieldvision.tracking.History";
	            $history->isVisible = $actual->isVisible;
	            $history->tsLastVisiblePosicion = $actual->tsLastVisiblePosicion;
	            $history = $this->createModel($history, $position);
	            //agrega la posicion a la salida
	            $positions[$key]['idHistory'] = $history->_id;
	            $data[] = ["reference" => $history->_id];
        	}
        }      

        $error = false;
        $msg = trans('general.MSG_OK');
        $total = 1;
        $intCode = 201;
        $view = null;

        $result = Util::outputJSONFormat($error, $msg, $total, $data, $view);
        return response($result, $intCode);
    }

    public function update (Request $request, $strId ){
        $error = false;
        $msg = trans('general.MSG_OK');
        $data = [["reference" => $strId]];
        $total = 1;
        $intCode = 200;
        $view = null;

        $result = Util::outputJSONFormat($error, $msg, $total, $data, $view);
        return response($result, $intCode);
    }

    public function delete (Request $request, $strId ){
        $error = false;
        $msg = trans('general.MSG_OK');
        $data = [["reference" => $strId]];
        $total = 1;
        $intCode = 200;
        $view = null;

        $result = Util::outputJSONFormat($error, $msg, $total, $data, $view);
        return response($result, $intCode);
    }

    public  function createModel( $model, $arrayFromJson )
    {
        $actualUser = Util::$insUser;

        //Instanciar
        $model->save();

        //Agregar resource
        $resource = $actualUser->replicate();
        $resource->_id = $actualUser->_id;
        $hiddenFields = $resource->getHidden();
        foreach ($hiddenFields as $hiddenField) {
            $resource->$hiddenField = null;
        }
        $model->resourceInstance()->save($resource);

        //Agregar icono
        $icon = Icon::where('type','=','resourceDefinitions')->where('name','=','Default')->first();
        $resourceDefinition = $actualUser->resourceDefinition;
        if($resourceDefinition){
            $icon = $resourceDefinition->icon;
        }
        $model->icon()->save($icon);

        return $model;
    }

    private function updateModel( $model, $arrayFromJson )
    {
        //
        $latitude = (float)$arrayFromJson["latitude"];
        $longitude = (float)$arrayFromJson["longitude"];

        //Encontrar empresa
        $actualUser = Util::$insUser;
        $actualCompany = $actualUser->company;
        $actualCountry = $actualCompany->country;
        $countryISO = $actualCountry->ISO;

        //Verificar que tengamos el geocoding para ese pais
        $collectionName = 'geocoding_'.$countryISO;
        if(Schema::connection('geocoding')->hasCollection($collectionName))
        {
            $geocodingResult = DB::connection('geocoding')
                ->table($collectionName)
                ->whereRaw([ 'location' => [ '$near' => [$latitude,$longitude], '$maxDistance' => 0.01 ] ])
                ->take(1)
                ->get();
            if(count($geocodingResult) > 0){
                $address = $geocodingResult[0]['name'].' '.$geocodingResult[0]['ciudad'].','.$geocodingResult[0]['departamento'];
            }
            else{
                $address = $actualCountry->country;
            }
        }
        else
        {
            $address = $actualCountry->country;
        }
        //Actualizar deviceData
        $deviceData = $model->deviceData;

        if(!$deviceData){
            $deviceData = new DeviceData();
            $model->deviceData()->save($deviceData);
        }

        $deviceDataInfo = new \stdClass();
        $deviceDataInfo->_class = "co.com.datatraffic.fieldvision.tracking.devices.Tablet";
        $deviceDataInfo->latitude = $latitude;
        $deviceDataInfo->longitude = $longitude;
        $deviceDataInfo->speed = $arrayFromJson["speed"];
        $deviceDataInfo->address = $address;
        $deviceDataInfo->heading = "";
        $deviceDataInfo->updateTime = new UTCDatetime(Carbon::createFromFormat('Y-m-d H:i:s', $arrayFromJson["updatetime"],'GMT-0')->getTimestamp() * 1000);
        $deviceDataInfo->ev = "00";
        $deviceDataInfo->ignitionStatus = "OFF";
        $deviceDataInfo->virtualOdometer = 0;
        $deviceDataInfo->savedtime = new UTCDatetime(Carbon::createFromFormat('Y-m-d H:i:s', $arrayFromJson["savedtime"],'GMT-0')->getTimestamp() * 1000);
        $deviceDataInfo->sourceGps = $arrayFromJson["sourceGps"];
        $deviceDataInfo->batteryLevel = $arrayFromJson["batteryLevel"];
        $deviceDataInfo->accuracy = $arrayFromJson["accuracy"];
        $deviceDataInfo->satellites = $arrayFromJson["satellites"];
        $deviceData->Tablet = $deviceDataInfo;
        $deviceData->save();

        //Actualizar actual
        $model->actualGeofences = [];
        $model->actualCheckPoints = [];
        $model->events = [];
        $model->latitude = $latitude;
        $model->longitude = $longitude;
        $model->updateTime = new UTCDatetime(Carbon::createFromFormat('Y-m-d H:i:s', $arrayFromJson["updatetime"],'GMT-0')->getTimestamp() * 1000);
        $model->speed = $arrayFromJson["speed"];
        $model->address = $address;
        $model->heading = 0;
        $model->hasEvent = false;
        $model->distance = 0;
        $model->virtualOdometer = 0;
        $model->totalDistance = 0;
        //$model->isVisible = true;
        $model->isGPRS = true;
        $model->ignitionStatus = "OFF";
        $actualUser = Util::$insUser;
        $model->id_company = $actualUser->id_company;

        if(array_key_exists('idHistory',$arrayFromJson))
        {
            $model->idHistory = $arrayFromJson['idHistory'];
        }

        //guardar
        $model->save();

        return $model;
    }
}