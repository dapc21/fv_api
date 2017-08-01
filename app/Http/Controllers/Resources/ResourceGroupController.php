<?php
namespace App\Http\Controllers\Resources;

use App\Http\Controllers\GenericMongo\DatatrafficController;
use App\Http\Controllers\GenericMongo\ControllerTraitCompanyCustomAttribute;
use App\Models\Resources\ResourceGroup;
use MongoDB\BSON\ObjectID;
use App\datatraffic\lib\Util;
use Illuminate\Support\Facades\DB;
use App\Models\Resources\ResourceInstance;
use MongoDB\Exception\RuntimeException;

class ResourceGroupController extends DatatrafficController
{
    use ControllerTraitCompanyCustomAttribute;
    
    //Nombre del modelo
    protected $modelo = 'App\Models\Resources\ResourceGroup';
    
    
    public function beforeDeleted( $id ){
    	//el resourceDefinition no se puede eliminar a si mismo
    	if( !Util::$insUser ) {
    		throw new RuntimeException('error.session_request');
    	}
    	//el resourceDefinition no debe estar asociado a otro recurso
    	$total = ResourceInstance::where('resourceGroups','=',['$ref' => 'resourceGroups', '$id' => new ObjectID( $id )])
    						      ->count();
    	if($total > 0){
    		throw new RuntimeException(trans('general.resource_group_has_resource_instances'));
    	}
    }
    
    
    public function afterDeleted( \Jenssegers\Mongodb\Eloquent\Model $model ){
    	
    }
}
