<?php
namespace App\Http\Controllers\Planning;

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
use App\Http\Controllers\GenericMongo\ControllerTraitCompanyCustomAttribute;
use App\Models\Planning\Route;
use Illuminate\Database\Eloquent\Collection;
use MongoDB\BSON\UTCDatetime;
use MongoDB\BSON\ObjectID;
use App\Models\Tracking\Event;
use App\Models\Tracking\History;
use App\Models\Resources\ResourceInstance;
use App\Models\Tracking\Actual;
use App\Models\Planning\Checkin;
use App\Models\Planning\Checkout;
use Illuminate\Support\Facades\Log;

class TaskController extends DatatrafficController
{
    //Nombre del modelo
    public $modelo = 'App\Models\Planning\Task';

    public function cancel($strIdTask) {
        $intCode = 200;
        $view = null;
        $error = false;
        $msg = trans('general.MSG_OK');
        $data = ["reference" => $strIdTask];
        $total = 1;

        $result = Util::outputJSONFormat($error, $msg, $total, $data, $view);

        return response($result, $intCode);
    }

    public function updateFromArray($arrData, $strId)
    {

        $idUserCreated = $this->dGetUserDBRefSession();

        //Arreglo de sincronizacion
        $arrRelationsSynchronize = [];
        if(isset($arrData['synchronize']))
        {
            $arrRelationsSynchronize = $arrData['synchronize'];
            unset($arrData['synchronize']);
        }

        //Encontrar modelo
        $insModel = new $this->modelo();
        $isModelFound = $insModel->find($strId);

        //Id a relacionar no encontrado
        if(!$isModelFound)
        {
            $e = new ModelNotFoundException();
            $e->setModel(get_class($insModel));
            throw $e;
        }

        $insModel = $isModelFound;

        $this->updateObject($insModel, $arrData, $arrRelationsSynchronize, $idUserCreated);

        $insModel = new $this->modelo();
        $isModelFound = $insModel->find($strId);
        $insModel = $isModelFound;
        $this->updateActual($insModel, []);

        return $strId;
    }

    private function updateActual($task, $register) {
        //Actualizar actual
        $nuevosdatos["tasks.$.status"] = $task->status;
        //$nuevosdatos["tasks.$.register"] = $register->toArray();
        $resource = Util::$insUser;

        DB::collection('actual')->where('resourceInstance._id',$resource->_id)->where("tasks._id",$task->_id)->update($nuevosdatos);
    }

    public function store(Request $request){
    	$response = parent::store($request);
    	$json = $response->getContent();
    	$data = json_decode($json,true);
    	if(!$data['error'])
    	{
    		//Recupera la tarea
    		$idTask = $data['data']['reference'];
    		$task=Task::where('_id',new ObjectID($idTask))->first();
    		//Recuperar recurso de la tarea
    		$resource= $task->resourceInstance;
    		if( !is_null( $resource ) ){
    			//Recuperar arrival_time de la tarea
    			$arrivalTime = $task->arrival_time;
    			$arrivalTime->hour   = 0;
    			$arrivalTime->minute = 0;
    			$arrivalTime->second = 0;    			
    			//Recupear Route del recurso de la tarea y date en arrival_time
    			$route= Route::where('date',$arrivalTime)
    				           ->where('resourceInstance._id',new ObjectID($resource->_id))
    						   ->first();
    			//dump(DB::connection()->getQueryLog());
    			//en caso de no existir se crea nuevo
    			if( is_null( $route ) ){
    				$newResource = $resource->replicate();
    				$newResource->_id = $resource->_id;
    				$route = new Route();    				
    				$route->id_company=$resource->id_company;
    				$route->tasks= [];
    				$route->date= $arrivalTime;
    				$route->status= "GENERATEDBYWEB";
    				$route->msgStatus= "OK";
    				$route->save();
    				$route->resourceInstance()->save($newResource);
    				
    			}
    			//Recuperar el arreglo de tareas del Route
    			$collectionTask= $route->tasks;
    			
    			if( is_null( $collectionTask )){
    				$collectionTask = new Collection();
    			}
    			
    			//Incluir la tarea nueva en el areglo de taraes
    			//Actualizar Royte con el nuevo arreglo
    			$route->tasks= [];
    			$route->save();
    			$insert=false;
    			foreach ($collectionTask as $key => $value ){
    				$newTask = $value->replicate();
    				$newTask->_id = $value->_id;
    				if ( $insert== false && $task->arrival_time->lte( $newTask->arrival_time ) ){
    					$insert=true;
    					$taskInsert= $task->replicate();
    					$taskInsert->_id = $task->_id;
    					$taskInsert->updated_at = $task->updated_at;
    					$taskInsert->created_at = $task->created_at;
    					$route->tasks()->save( $taskInsert  );
    				}
    				$route->tasks()->save( $newTask  );
    			}
    			if ( $insert == false ){
    				$insert=true;
    				$taskInsert= $task->replicate();
    				$taskInsert->_id = $task->_id;
    				$taskInsert->updated_at = $task->updated_at;
    				$taskInsert->created_at = $task->created_at;
    				$route->tasks()->save( $taskInsert  );
    			}
    		}    
    	}    
    	return $response;
    }
    
    public function update( Request $request, $strId ){
    	//Log::info("INCIA UPDATE TASK ".$strId);
    	DB::connection()->enableQueryLog();
    	$oldTask=Task::where('_id',new ObjectID( $strId ))->first();
    	$response = parent::update($request, $strId);
    	$json = $response->getContent();
    	$data = json_decode($json,true);
    	if(!$data['error']) {
    		//Recuperar la tarea con $strId
    		$task=Task::where('_id',new ObjectID( $strId ))->first();
    		//Recupera el recurso de la tarea
    		$resource= $task->resourceInstance;
    		
    		$nuevosdatos=[
    				"tasks.$.status"=>$task->status
    		];
    		
    		$checkin= $task->checkin;
    		$checkout=$task->checkout;
    		
    		if( !is_null($checkin) ){
    			$nuevosdatos['tasks.$.checkin']=[
    					'date'=>new UTCDatetime(Carbon::createFromFormat('Y-m-d H:i:s',$checkin['date'],'GMT-0')->getTimestamp() * 1000),
    					'location'=>['lat'=>$checkin['location']['lat'], 'lng'=>$checkin['location']['lng']],
    			];
    		}
    		$checkout=$task->checkout;
    		if( !is_null($checkout) ){
    			$nuevosdatos['tasks.$.checkout']=[
    					'date'=>new UTCDatetime(Carbon::createFromFormat('Y-m-d H:i:s',$checkout['date'],'GMT-0')->getTimestamp() * 1000),
    					'location'=>['lat'=>$checkout['location']['lat'], 'lng'=>$checkout['location']['lng']],
    			];
    		}
    		//dd($checkin,$checkout,$nuevosdatos);
    		
    		$resource = Util::$insUser;
    		DB::collection('routes')->where('resourceInstance._id',$resource->_id)
    		                        ->where("tasks._id",$task->_id)
    		                        ->update($nuevosdatos);
    		//Recuperar arrival_time de la tarea
    		$arrivalTime = $task->arrival_time;
    		$arrivalTime->hour   = 0;
    		$arrivalTime->minute = 0;
    		$arrivalTime->second = 0;
    		$datetime=new UTCDatetime(Carbon::createFromFormat('Y-m-d H:i:s',$arrivalTime,'GMT-0')->getTimestamp() * 1000);
    		$pipeline = [
	    		 ['$match' =>[ '$and'=>[ ['resourceInstance._id'=>new \MongoDB\BSON\ObjectID($resource->_id)  ],
	    		 		                 ['date'=>$datetime],
	    		 		                 ['deleted_at'=>null],
	    		 					   ]  
	    		 		     ]    		 		
	    		 ],
	    		 ['$project' => [ '_id'=>0, 'tasks'=>1 ]],
	    		 ['$unwind' => '$tasks'],
	  			 ['$group' => [ '_id'=>'$tasks.status', 'cantdad'=> [ '$sum'=> 1 ] ]  ],
    		];
    		
  			$options = [
  					'typeMap' => [
  							'root' => 'array',
  							'document' => 'array',
  					]
  			];
  			$groups= iterator_to_array(DB::collection('routes')
  					    ->raw()
    		    		->aggregate( $pipeline,$options )
  					 );
    		//recalcula las estadisticas
    		$statistics =[
	    		'totalPending'   =>0,
	    		'totalCancelled' =>0,
	    		'totalCheckin' =>0,
	    		'totalCheckoutWithForm'    =>0,
	    		'totalCheckoutWithoutForm' =>0,
	    		'totalApproved'  =>0,
    		];
    		foreach ($groups as $value){
    			$status=$value['_id'];
    			switch ($status){
    				case "PENDIENTE":
    					$statistics['totalPending']   =$value['cantdad'];
    					break;
    				case "CHECKIN":
    					$statistics['totalCheckin'] =$value['cantdad'];
    					break;
    				case "CANCELADA":
    					$statistics['totalCancelled'] =$value['cantdad'];
    					break;
    				case "CHECKOUT SIN FORMULARIO":
    					$statistics['totalCheckoutWithoutForm'] =$value['cantdad'];
    					break;
    				case "CHECKOUT CON FORMULARIO":
    					$statistics['totalCheckoutWithForm'] =$value['cantdad'];
    					break;
    				case "APROBADA":
    					$statistics['totalApproved']  =$value['cantdad'];
    					break;
    				default:
    					break;
    			}
    		}
    		//actualiza las estadisticas
    		Route::where( 'date',$arrivalTime )
    				->where( 'resourceInstance._id',new ObjectID($resource->_id) )
    				->update( ['statistics'=>$statistics ] );
    		//generar el evento
    		$status=$task->status;
    		$event = null;
    		$date=null;
    		$lat=0;
    		$long=0;
    		switch ($status) {
    			case "CHECKIN":
    				$checkin= $task->checkin;
    				$date= $checkin['date'];
    				$lat=  $checkin['location']['lat'];
    				$long= $checkin['location']['lng'];
    				$event = new Event();
    				$event->eventCategory = "PLANNING";
    				$event->eventType = 'CHECKIN';
    				$event->code = 'EVCHECKIN';
    				$event->description = trans("la tarea cambio de estado de ")." ".$oldTask->status. "  ".trans("a")." ".$task->status;
    				break;
    			case "CHECKOUT SIN FORMULARIO":
    				$checkout= $task->checkout;
    				$date= $checkout['date'];
    				$lat=  $checkout['location']['lat'];
    				$long= $checkout['location']['lng'];
    				$event = new Event();
    				$event->eventCategory = "PLANNING";
    				$event->eventType = 'CHECKOUT';
    				$event->code = 'EVCHECKOUTWITHOUTFORM';
    				$event->description = trans("la tarea cambio de estado de ")." ".$oldTask->status. "  ".trans("a")." ".$task->status;
    				break;
    			case "CHECKOUT CON FORMULARIO":
    				$checkout= $task->checkout;
    				$date= $checkout['date'];
    				$lat=  $checkout['location']['lat'];
    				$long= $checkout['location']['lng'];
    				$event = new Event();
    				$event->eventCategory = "PLANNING";
    				$event->eventType = 'CHECKOUT';
    				$event->code = 'EVCHECKOTWITHFORM';
    				$event->description = trans("la tarea cambio de estado de ")." ".$oldTask->status. "  ".trans("a")." ".$task->status;
    				
    				break;
    			default:
    				break;
    		}
    		//si genera un evento  se actualiza en actual y en history segun sea el caso
    		if( !is_null( $event ) ){
    			$compactResource= new ResourceInstance();
    			$compactResource->_id= $resource->_id;
    			$compactResource->id_company= $resource->id_company;
    			$compactResource->login= $resource->login;
    			$compactResource->id_resourceDefinition= $resource->id_resourceDefinition;
    			$compactResource->resourceGroups= $resource->resourceGroups;
    			
    			$taskInsert= $task->replicate();
    			$taskInsert->_id = $task->_id;
    			$taskInsert->updated_at = $task->updated_at;
    			$taskInsert->created_at = $task->created_at;
    			$updateTimeTask=new UTCDatetime(Carbon::createFromFormat('Y-m-d H:i:s',$date,'GMT-0')->getTimestamp() * 1000);
    			$event->updateTime = $updateTimeTask;
    			$event->save();
    			$event->task()->save($taskInsert);
    			$event->resourceInstance()->save($compactResource);
    			//guarda el evento en la collection events
    			$actual= Actual::where("resourceInstance._id", new ObjectID($resource->id))->first();
    			if(  ! is_null( $actual ) ){
    				$updateTime= $actual->updateTime;
    				$minutos= $updateTime->diffInMinutes( $date , true);
    				//si no han transcurrido mas de 10 minutos actualiza la tarea
    				$history = null;
    				//se verifica si el evento es reciente  y lo guarda en actual e history
    				if ( abs($minutos) < 10 ) {
    					$newEvent=$event->replicate();
    					$newEvent->_id = $event->_id;
    					$actual->hasEvent=true;
    					$actual->save();
    					
    				}
    				if(is_null( $actual->idHistory ) ){
                        $newEvent=$event->replicate();
                        $newEvent->_id = $event->_id;
    					$actual->events()
    						   ->save($newEvent);
    					$history= History::where("_id",new ObjectID( $actual->idHistory) )
    					                 ->first();
    				}else{
    					$history = History::where("resourceInstance._id", new ObjectID($resource->id))
    						              ->where("updateTime",array( '$gte'=>$updateTimeTask ))
    						              ->orderBy('updateTime',-1)
    						              ->first();
    				}
    				//adiciona el evento a la colleccion
    				if( !is_null( $history ) ){
    					$newEvent=$event->replicate();
    					$newEvent->_id = $event->_id;
    					
    					$history->hasEvent=true;
    					$history->save();
    					$history->events()
    					        ->save($newEvent);
    				}
    			}
    		}	
    	}
    	
    	//LOG::info(DB::connection()->getQueryLog());
    	return $response;
    }

    public function saveTaskStatusPhoto(Request $request, $strIdTask){
        //Encontrar tarea
        $task = Task::where('_id','=',new ObjectID($strIdTask))->first();
        if($task) {
            $path = public_path('images');
            Util::saveFiles($request, $path);
        }
		else {
			$e = new ModelNotFoundException();
			$e->setModel('Task');
			throw $e;			
		}
		
        $error = false;
        $msg = trans('general.MSG_OK');
        $data = ["reference" => $strIdTask];
        $total = 1;
        $intCode = 201;
        $view = [];
        
        $result = Util::outputJSONFormat($error, $msg, $total, $data, $view);

        return response($result, $intCode);		
    }

    protected function getCustomAttributes($dataArray)
    {
        $customAttributes = [];

        if(!Util::$manageAllCompanies) {
            $DBRefCompany = Util::$insUser->id_company;
            $id_company = $DBRefCompany['$id']->__toString();

            $customAttributes['id_company'] = $id_company;
        }

        if(!Util::$manageAllResource) {
            $DBRefResourceInstance = Util::$insUser->getDBRef();
            $id_resourceInstance = $DBRefResourceInstance['$id']->__toString();

            $customAttributes['id_resourceInstance'] = $id_resourceInstance;
        }

        return $customAttributes;
    }
}
