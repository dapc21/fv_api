<?php
namespace App\Http\Controllers\Resources;

use App\Models\Resources\ResourceDefinition;
use App\Models\Resources\ResourceInstance;
use App\Models\Scheduling\StartEndLocation;
use Carbon\Carbon;
use App\datatraffic\lib\Util;
use App\datatraffic\lib\Configuration;
use App\datatraffic\lib\ErrorMessages;
use App\datatraffic\dao\Generic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Auth;
use App\Http\Controllers\GenericMongo\DatatrafficController;
use MongoDB\BSON\ObjectID;
use MongoDB\Exception\RuntimeException;

class ResourceDefinitionController extends DatatrafficController
{
    //Nombre del modelo
    protected $modelo = 'App\Models\Resources\ResourceDefinition';

    //Titulo del reporte
    protected $excelTitle = 'Definiciones de recurso';

    //Titulo del reporte
    protected $excelAttributes = '{"name":1, "company":{"name":1}, "customAttributes":{"fieldLabel":1, "xtype":1, "allowBlank":1, "mandatory":1}, "resourceDefinitions": {"name":1,"customAttributes":{"fieldLabel":1, "xtype":1, "allowBlank":1, "mandatory":1}}, "limit":1, "status":1, "routingTool":1}';

    //Manejo de Atributos
    use \App\Http\Controllers\Resources\ControllerTraitCustomAttribute;
    //Manejo de los Dispositivos
    use \App\Http\Controllers\Resources\ControllerTraitDeviceDefinition;
    //Manejo de los Recursos
    use \App\Http\Controllers\Resources\ControllerTraitResourceDefinition;

    public function store(Request $request){
        $response = parent::store($request);
        $json = $response->getContent();
        $data = json_decode($json,true);
        if(!$data['error'])
        {
            $idResourceDefinition = $data['data']['reference'];
            $resouseDefinition = ResourceDefinition::find($idResourceDefinition);
            $company = $resouseDefinition->company;

            $startEndLocation = new StartEndLocation();
            $startEndLocation->_id = new ObjectID();
            $startEndLocation->id_resourceDefinition = ['$ref' => "resourceDefinitions", '$id' => new ObjectID($idResourceDefinition)];
            $startEndLocation->start = [
                "name" => "DATATRAFFIC SAS",
                "address" => "Parque Bolívar Medellín, Colombia",
                "location" => [
                    "lat" => 6.253041,
                    "lng" => -75.564574
                ]
            ];
            $startEndLocation->end = [
                "name" => "DATATRAFFIC SAS",
                "address" => "Parque Bolívar Medellín, Colombia",
                "location" => [
                    "lat" => 6.253041,
                    "lng" => -75.564574
                ]
            ];

            $company->startEndLocations()->save($startEndLocation);
        }

        return $response;
    }

    /**
     * Función principal encargada de actualizar los datos enviados
     */
    public function update(Request $request, $strId)
    {
        $response = parent::update($request, $strId);
        $json = $response->getContent();
        $data = json_decode($json,true);
        if(!$data['error']) {
            //Actualizar iconos de actual
            $resourceDefinition = ResourceDefinition::where('_id','=',new ObjectID($strId))->first();
            if($resourceDefinition) {
                $icon = $resourceDefinition->icon;
                if ($icon) {
                    $iconArray = ['icon' => $icon->toArray()];
                    DB::collection('actual')->where('resourceInstance.id_resourceDefinition', $resourceDefinition->getDBRef())->update($iconArray);
                }
            }
        }

        return $response;
    }

    protected function getCustomAttributes($dataArray)
    {
        $customAttributes = [];

        //Si el usuario no tiene permiso para administrar todas las empresas
        //entonces se debe asignar como empresa la misma del usuario que inicio sesion
        if(!Util::$manageAllCompanies) {
            $DBRefCompany = Util::$insUser->id_company;
            $id_company = $DBRefCompany['$id']->__toString();
            $customAttributes['id_company'] = $id_company;
        }

        $customAttributes['isSystem'] = false;

        return $customAttributes;
    }
   	
    
    public function beforeDeleted( $id ){
    	//el resourceDefinition no se puede eliminar a si mismo
    	if(Util::$insUser){
    		$resourceDef = Util::$insUser->id_resourceDefinition;
    		if (  strcmp($resourceDef['$id'].'', $id )==0  ){
    			throw new RuntimeException('error.can_not_deleted_the_same_resource_definition');
    		}
    	} else {
    		throw new RuntimeException('error.session_request');
    	}
    	$resourceDef= ResourceDefinition::where('_id', new ObjectID( $id) )->first();
    	if($resourceDef->isSystem == true ){
    		throw new RuntimeException(trans('general.can_not_delete_resource_definition'));
    	}
    	//el resourceDefinition no debe estar asociado a otro recurso
    	$totalResourceInstance = ResourceInstance::where('id_resourceDefinition','=',['$ref' => 'resourceDefinitions', '$id' => new ObjectID( $id )])->count();
    	if($totalResourceInstance > 0){
    		throw new RuntimeException(trans('general.resource_definition_has_resource_instances'));
    	}
    }
    
    
    public function afterDeleted( \Jenssegers\Mongodb\Eloquent\Model $model ){
    	if($model){
    		//quita todas las referencias del tipo de recurso asociadas a la empresa
    		$company = $model->company;
    		$startEndLocations= $company->startEndLocations;
    		DB::collection('companies')
    		    ->where( '_id', $company->_id )
    		    ->pull('startEndLocations',['id_resourceDefinition'=>['$ref' => 'resourceDefinitions', '$id' => new ObjectID( $model->_id )] ]);
    	}
    }
}
