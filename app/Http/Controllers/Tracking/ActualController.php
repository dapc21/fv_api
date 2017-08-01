<?php

namespace App\Http\Controllers\Tracking;

use App\datatraffic\lib\Util;
use App\Http\Controllers\GenericMongo\DatatrafficController;
use App\Models\Planning\Route;
use App\Models\Tracking\Actual;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Http\Request;
use DB;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDatetime;
use \COM;
use Log;

class ActualController extends DatatrafficController
{
    //Nombre del modelo
    protected $modelo = 'App\Models\Tracking\Actual';

    public function index(Request $request) {
        //DB::connection()->enableQueryLog();

        //Parametros
        $sorts = $request->has('sort') ? json_decode($request->get('sort'), true) : [];
        $filters = $request->has('filters') ? json_decode($request->get('filters'), true) : [];
        $relations = $request->has('relations') ? json_decode($request->get('relations')) : [];
        $view = $request->has('view') ? json_decode($request->get('view')) : [];

        $page = $request->has('page') ? $request->get('page') : 1;
        $limit = $request->has('limit') ? $request->get('limit') : 15;

        //Recuperar la fecha de las tareas a consultar y las condiciones de filtrado para tareas
        $taskConditions = [];
        $date = Carbon::today('GMT-0')->toDateTimeString();
        foreach($filters as $key => $filter)
        {
            //Nivel AND o OR
            foreach($filter as $subKey => $subFilter)
            {
                if($subFilter['field'] === 'route.date')
                {
                    $date = $subFilter['value'];
                    unset($filters[$key][$subKey]);
                }
                else if($subFilter['field'] === 'route.tasks.status')
                {
                    $statusArray = $subFilter['value'];
                    if(is_array($statusArray))
                    {
                        $statusOR = [];
                        foreach ($statusArray as $item) {
                            $statusOR[] =  ['$eq' => ['$$task.status', $item]];
                        }

                        if(!empty($statusOR)) {
                            $taskConditions['$and'][] = ['$or' => $statusOR];
                        }
                    }
                    else
                    {
                        $taskConditions['$and'][] =  ['$eq' => ['$$task.status', $statusArray]];
                    }

                    unset($filters[$key][$subKey]);
                }
                else if($subFilter['field'] === 'route.tasks.code')
                {
                    $taskConditions['$and'][] = ['$eq' => ['$$task.code', $subFilter['value']]];
                    unset($filters[$key][$subKey]);
                }
                else if($subFilter['field'] === 'route.tasks.name')
                {
                    $taskConditions['$and'][] = ['$eq' => ['$$task.name', $subFilter['value']]];
                    unset($filters[$key][$subKey]);
                }
                else if($subFilter['field'] === 'route.tasks.arrival_time')
                {
                    $arrival_time = new UTCDatetime(Carbon::createFromFormat('Y-m-d H:i:s',$subFilter['value'],'GMT-0')->getTimestamp() * 1000);
                    $taskConditions['$and'][] = ['$gt' => ['$$task.arrival_time', $arrival_time]];
                    unset($filters[$key][$subKey]);
                }
                else if($subFilter['field'] === 'route.tasks._id')
                {
                    $statusArray = $subFilter['value'];
                    if(is_array($statusArray))
                    {
                        $statusOR = [];
                        foreach ($statusArray as $item) {
                            $statusOR[] =  ['$eq' => ['$$task._id', new ObjectID($item)]];
                        }

                        if(!empty($statusOR)) {
                            $taskConditions['$and'][] = ['$or' => $statusOR];
                        }
                    }
                    else
                    {
                        $taskConditions['$and'][] =  ['$eq' => ['$$task._id', $statusArray]];
                    }

                    unset($filters[$key][$subKey]);
                }
            }
        }
        $dateRoute = new UTCDatetime(Carbon::createFromFormat('Y-m-d H:i:s',$date,'GMT-0')->getTimestamp() * 1000);

        //Obtener el query
        $object = new Actual();
        $query = $object->withoutGlobalScope(new SoftDeletingScope());

        //Filtrar
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

        //Hacer copia del query
        $queryCopy = clone $query;

        //Relaciones
        if (!empty($relations)) {

            //La relacion route porque es un caso especial
            if(in_array('route',$relations))
            {
                //Eliminar route del arreglo de relaciones
                unset($relations[array_search('route', $relations)]);

                //Para traer las tareas y la ruta
                $query = $query->with(['route' => function($queryRelation) use ($dateRoute, $taskConditions){
                    $queryRelation->where('date', '=', $dateRoute);

                    if(!empty($taskConditions)) {
                        $eloquentBuilder = $queryRelation->getQuery();
                        $queryBuilder = $eloquentBuilder->getQuery();
                        $queryBuilder->aggregate = ['function' => '', 'columns' => []];
                        $queryBuilder->projections =
                            [
                                'tasks' =>
                                    [
                                        '$filter' => [
                                            'input' => '$tasks',
                                            'as' => 'task',
                                            'cond' =>  $taskConditions
                                        ]
                                    ],
                                'resourceInstance._id' => 1,
                                'rawShape' => 1,
                                '_id' => 1,
                            ];
                        $eloquentBuilder->setQuery($queryBuilder);
                        $queryRelation->setQuery($eloquentBuilder);
                    }
                }]);
            }

            //Adjuntas las demas relaciones
            if(count($relations)>0)
            {
                $query = $this->relations($object, $query, $relations);
            }
        }

        //Ordenamiento
        if (!empty($sorts)) {
            $query = $this->orders($object, $query, $sorts);
        }

        //Paginar
        $result = $this->paginate($object, $query, $page, $limit, $view);

        //Estadisticas
        $statistics = $this->getStatisticsFromActual($queryCopy, $dateRoute, $taskConditions);

        $error = false;
        $msg = trans('general.MSG_OK');
        $data = $result->toArray();
        $data['metaData']['statistics'] = $statistics;
        $total = 1;
        $intCode = 200;

        $result = Util::outputJSONFormat($error, $msg, $total, $data, $view);

        //dump(DB::connection()->getQueryLog());

        return response($result, $intCode);
    }

    private function getStatisticsFromActual($query, $dateRoute, $taskConditions) {
        //Obtener unicamente los ids de los recursos que interviene en esta consulta de actual
        $routeConditions = [];
        $resourceInstanceIds = $query->project(['resourceInstance._id' => 1])->get();
        $statusOR = [];
        foreach ($resourceInstanceIds as $resourceInstanceId){
            $statusOR[]  =  ['resourceInstance._id' => new ObjectID($resourceInstanceId->resourceInstance['_id'])];
        }
        if(!empty($statusOR))
        {
            $routeConditions['$and'][] = ['$or' => $statusOR];
        }
        $routeConditions['$and'][] =  ['deleted_at' => null];
        $routeConditions['$and'][] =  ['date' => $dateRoute];

        //Calcular estadisticas de la consulta
        if(!empty($taskConditions)) {
            $project = [
                '_id' => 0,
                'tasks' =>[
                    '$filter' => [
                        'input' => '$tasks',
                        'as' => 'task',
                        'cond' =>  $taskConditions
                    ]
                ]
            ];
        }
        else {
            $project = [
                '_id' => 0,
                'tasks' =>1
            ];
        }

        $pipeline =[
            [
                '$match' => $routeConditions
            ],
            [
                '$project' => $project
            ],
            ['$unwind' => '$tasks'],
            ['$group' => ['_id' => '$tasks.status', 'cantidad' => ['$sum' => 1]]]
        ];

        $options = [
            'typeMap' => [
                'root' => 'array',
                'document' => 'array',
            ]
        ];
        $collection = \Illuminate\Support\Facades\DB::collection('routes')->raw();
        $statistics = iterator_to_array($collection->aggregate($pipeline, $options));

        $result = [];
        $result['totalPending'] = 0;
        $result['totalCheckin'] = 0;
        $result['totalCheckoutWithForm'] = 0;
        $result['totalCheckoutWithoutForm'] = 0;
        $result['totalCancelled'] = 0;
        $result['totalApproved'] = 0;
        foreach ($statistics as $statistic){
            switch ($statistic['_id']){
                case 'PENDIENTE':
                    $result['totalPending'] = $statistic['cantidad'];
                    break;
                case 'CHECKIN':
                    $result['totalCheckin'] = $statistic['cantidad'];
                    break;
                case 'CHECKOUT CON FORMULARIO':
                    $result['totalCheckoutWithForm'] = $statistic['cantidad'];
                    break;
                case 'CHECKOUT SIN FORMULARIO':
                    $result['totalCheckoutWithoutForm'] = $statistic['cantidad'];
                    break;
                case 'CANCELADA':
                    $result['totalCancelled'] = $statistic['cantidad'];
                    break;
                case 'APROBADA':
                    $result['totalApproved'] = $statistic['cantidad'];
                    break;
            }
        }

        return $result;
    }
	
    /**
     * 
     * @param Request $request
     * @param unknown $type   PlanningTracking|ExportActualResourceTracking
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function excelCustom(Request $request, $type ) {
        //Usuario
        $actualUser = Util::$insUser;
        $parameters = ' --resource="'.$actualUser->_id.'"';

        //Filtros
        if($request->has('filters')) {
            $filters = addslashes($request->get('filters'));
            $parameters .= ' --filters="'.$filters.'"';
        }
        
        $report="";
        switch ($type){
        	case "planningtracking":
        		$report="ExportPlanningTracking";
        		break;
        	case "actualresourcetracking":
        		$report="ExportActualResourceTracking";
        		break;
        }
		
        $result =[];
        //Ejecutar comando
        if($report != ""){
	        //Ejecutar comando
	        if(env('OS','LINUX') == 'Windows_NT')
	        {
	            $cmd = 'php.exe '.base_path().'\artisan '.$report.' '.$parameters;
	            $WshShell = new COM("WScript.Shell");
	            $WshShell->Run($cmd, 0, false);
	        }
	        else
	        {
	            $cmd = 'php '.base_path().'/artisan '.$report.' '.$parameters;
	            exec($cmd . " > /dev/null &");
	        }
	        
	        $error = false;
	        $msg = trans('general.MSG_OK');
	        $data = [$cmd];
	        $total = 1;
	        $intCode = 200;
	        $view = [];
	        $result = Util::outputJSONFormat($error, $msg, $total, $data, $view);
        }else{
        	$error = true;
        	$msg = trans('general.MSG_ERROR_EXPORT');
        	$data = ['No existe '.$type];
        	$total = 1;
        	$intCode = 404;
        	$view = [];
        	$result = Util::outputJSONFormat($error, $msg, $total, $data, $view);
        }

       

        

        return response($result, $intCode);
    }
}
