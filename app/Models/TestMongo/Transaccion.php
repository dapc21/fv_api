<?php
namespace App\Models\TestMongo;

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use App\datatraffic\lib\Util;
use datatraffic\lib\Configuration;
use Illuminate\Support\Facades\DB;
//use \Illuminate\Database\Eloquent\SoftDeletes;

use Jenssegers\Mongodb\Model as Moloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Mongodb\Connection;

class Transaccion extends Moloquent {
    use SoftDeletes;    
    use \App\models\generic\GenericModelTrait;
    
    protected $collection = 'transacciones';
    //estas dos son equivalente, con más prioridad collections
    protected $table = 'public.test_transaccion';
    //Para softdeleted
    protected $dates = ['deleted_at'];
    
    //Nombre de la columna que hace parte de llave primaria
    protected $primaryKey = 'id_transaccion';
    //Nombre de los campos calculados
    protected $appends = array();
    
    protected static $status = array(
        'start' => 'start',
        'completed' => 'completed',
        'error' => 'error',
        'recovered' => 'recovered',
    ); 


    public function begin() {
        $id = $this->primaryKey;
        $collection = $this->collection;
        
        $this->$id = $this->getNextSequence($collection);
        $this->status = self::$status['start'];
        $this->save();
    }
    
    public function commit() {
        $this->status = self::$status['completed'];
        $id = $this->primaryKey;
        
        //Limpiamos las clases
        foreach($this->data as $valueData){
            $modelClass = new $valueData->class;
            $modelClass::where('id_transaccion', $this->$id)->withTrashed()->unset('id_transaccion');
        }
        $this->save();
    }
    
    public function rollback() {
        $this->status = self::$status['error'];
        $this->save();
        
        $id = $this->primaryKey;
        $relationDelete = [];
        
        //Limpiamos las clases
        foreach($this->data as $valueData){
            $modelClass = new $valueData->class;
            
            //Volvemos al estado anterior si fue un update
            if(property_exists($valueData, 'id')){
                
                if(property_exists($valueData, 'relation')){
                    $model = new $modelClass;
                    $modelPrimaryKey = $model->getPrimaryKey();
                    $relationObject = $modelClass::where($modelPrimaryKey, "=", ((int)$valueData->id))->withTrashed()->first();
                    $relationName = $valueData->relation;
                    
                    if(!array_key_exists($relationName, $relationDelete)){
                        $allObjects = $relationObject->$relationName()->all();
                        foreach ($allObjects as $object)
                                $object->delete();
                    }
                    $relationDelete[$relationName] = $valueData->id;
                        
                    
                    $relationshipMap = $model->getRelationshipMap();
                    $relationConfig = $relationshipMap[$relationName];
                    $oldObjectController = new $relationConfig["foreign_controller"];

                    $strOldObjectController = $oldObjectController->getModelo();

                    $oldObject = new $strOldObjectController;
                    $dataOld = is_array($valueData->data)? $valueData->data : json_decode($valueData->data, true);
                    
                    foreach ($dataOld as $key => $value){
                        if($value == null)
                            isset($oldObject->$key);
                        else
                            $oldObject->$key = $value;
                    }
                    
                    $relationObject->$relationName()->save($oldObject);
                }else{
                    $oldObject = $modelClass::where('id_transaccion', "=", ((int)$this->$id))->first();
                    
                    if(empty($oldObject)){
                        $oldObject = $modelClass::where('id_transaccion', "=", ((int)$this->$id))->withTrashed()->first();
                        $oldObject->deleted_at = null;
                        isset($oldObject->deleted_at);
                    }else{
                        foreach ($valueData->data as $key => $value){
                            if($value == null)
                                isset($oldObject->$key);
                            else
                                $oldObject->$key = $value;
                        }
                    }
                    
                    $oldObject->save();
                    $modelClass::where('id_transaccion', $this->$id)->unset('id_transaccion');
                }
            }else{
                $modelClass::where('id_transaccion', $this->$id)->delete();
            }
        }
        
        $this->status = self::$status['recovered'];
        $this->save();
    }
    
    //Nombre de las columnas que se mostraran en la version simple de select y show
    /*protected $view = [
	    		'list' => [
                            'id_vehiculo',
                            'placa'
                        ],
	    		'simple' => [
                            'id_vehiculo',
                            'placa'
                        ],
                'full' => [
                            'id_vehiculo',
                            'placa',
                            'id_empresa'
                        ]
    		];
    //mapeo de columnas
    protected $columns_map = [
                                '_id' => ['name'=>'_id', 'filter'=> '' , 'editable'=> true, 'type'=>'numeric'],
                                'id_vehiculo' => ['name'=>'id_vehiculo', 'filter'=> '' , 'editable'=> true, 'type'=>'numeric'],
                                'placa'  =>  ['name' =>'placa', 'filter'=> '' , 'editable'=> true, 'type'=>'string'],
                                'id_empresa'=>['name' => 'id_empresa', 'filter'=> '' , 'editable'=> true, 'type'=>'numeric'],
                                'capacidad_combustible' => ['name'=>'capacidad_combustible', 'filter'=> '' , 'editable'=> true, 'type'=>'numeric'],
                                'ultimo_login' => ['name'=>'ultimo_login', 'filter'=> '' , 'editable'=> true, 'type'=>'timestamp'],
                                'configuracion' => ['name'=>'configuracion', 'filter'=> '' , 'editable'=> true, 'type'=>'json'],
                                'deleted_at' =>   ['name'=>'deleted_at', 'filter'=> '' , 'editable'=> true, 'type'=>'timestamp'],
                                'created_at' =>   ['name'=>'created_at', 'filter'=> '' , 'editable'=> true, 'type'=>'timestamp'],
                                'updated_at' =>   ['name'=>'updated_at', 'filter'=> '' , 'editable'=> true, 'type'=>'timestamp'],
                                'id_user_create' => ['name'=>'id_user_create', 'filter'=> '' , 'editable'=> true,'type'=>'numeric'],
                                'id_user_update' => ['name'=>'id_user_update', 'filter'=> '' , 'editable'=> true,'type'=>'numeric'],
                              ];
     
    //mapeo de relaciones
    protected $relationship_map =[
                                    'empresa'=>[ 
                                                 'type'  =>'onetoone',
                                                 'foreign_controller' => 'App\Http\Controllers\TestMongo\EmpresaController' ,
                                                 'pivot_table'     => 'public.test_empresa',
                                                 'pivot_sequence'  => '',
                                                 'pivot_id'        => '',
                                                 'pivot_id_parent' => 'id_empresa',
                                                 'pivot_id_foreign'=> 'id_empresa',
                                                 'action'          => 'create'
                                               ],
                                    'dispositivos' => [ 
                                                 'type'  =>'embedsmany',
                                                 'foreign_controller' => 'App\Http\Controllers\TestMongo\DispositivoController',
                                                 'pivot_table'     => 'public.test_dispositivo',
                                                 'pivot_sequence'  => '',
                                                 'pivot_id'        => '',
                                                 'pivot_id_parent' => 'id_vehiculo',
                                                 'pivot_id_foreign'=> 'id_vehiculo',
                                                 'action'          => 'create'
                                               ],
                                    'conductores' =>[
                                                'type'  => 'manytomany',
                                                'foreign_controller' => 'App\Http\Controllers\TestMongo\ConductorController',
                                                'pivot_table'      => 'public.test_vehiculo_conductor',
                                                'pivot_sequence'   => 'public.seq_test_vehiculo_conductor_id',
                                                'pivot_id' => 'id_vehiculo_conductor',
                                                'pivot_id_parent'  => 'id_vehiculo',
                                                'pivot_id_foreign' => 'id_conductor'
                                              ],
                                 ];
    
     
    //Asociaciones permitidas
    protected $whiteWith = ["empresa" , "dispositivos" , "conductores" ];
    
    protected $colums_identify = [];
    protected $colums_title =[];
    protected $excel_with_tables =[];
    protected $excel_change_data =[];*/
    
    
    
    
    /**
     *
     * @param unknown $query
     */
    /*public function scopeCustomWhere($query)
    {
    	return $query;
    }*/
   
    //RELACIONES
    
    /*public function empresa()
    {
    	return $this->belongsTo('App\models\TestMongo\Empresa', 'id_empresa', 'id_empresa');
    }
    
    public function conductores() {
        return $this->belongsToMany('App\models\TestMongo\Conductor', null, 'vehiculos', 'conductores');
    }
    
    public function dispositivos() {
        return $this->embedsMany('App\models\TestMongo\Dispositivo');
    }*/
    
    /*public function toArray()
    {
    	$array = parent::toArray();
    	$resultado = array();

        //sql
        foreach ($array as $key => $value)
        {
            switch($this->columns_map[$key]['type'])
            {
                case 'json':
                    $resultado[$key] = json_decode($value,true);
                    break;
                default:
                    $resultado[$key] = $value;
                    break;
            }
        }
    	return $resultado;        
    }*/
}
