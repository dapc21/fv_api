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

class PivotVehiculosConductores extends Moloquent {
    use SoftDeletes;    
    use \App\models\generic\GenericModelTrait;
    
    protected $collection = 'pivot_vehiculos_conductores';
    //estas dos son equivalente, con más prioridad collections
    protected $table = 'public.pivot_vehiculos_conductores';
    //Para softdeleted
    protected $dates = ['deleted_at'];
    //Nombre de la columna que hace parte de llave primaria
    protected $primaryKey = 'id_vehiculo_conductor';
    //Nombre de los campos calculados
    protected $appends = array();
    
    //Nombre de las columnas que se mostraran en la version simple de select y show
    protected $view = [
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
                                'id_vehiculo_conductor'=>['name' => 'id_conductor', 'filter'=> '' , 'editable'=> true, 'type'=>'numeric'],
                                'id_vehiculo' => ['name'=>'id_vehiculo', 'filter'=> '' , 'editable'=> true, 'type'=>'numeric'],
                                'id_conductor'=> ['name' => 'id_conductor', 'filter'=> '' , 'editable'=> true, 'type'=>'numeric'],
                                'dias' => ['name'=>'dias', 'filter'=> '' , 'editable'=> true, 'type'=>'string'],
                                'hora_inicio' => ['name'=>'hora_inicio', 'filter'=> '' , 'editable'=> true, 'type'=>'string'],
                                'hora_fin' => ['name'=>'hora_fin', 'filter'=> '' , 'editable'=> true, 'type'=>'string'],
                                'deleted_at' =>   ['name'=>'deleted_at', 'filter'=> '' , 'editable'=> true, 'type'=>'timestamp'],
                                'created_at' =>   ['name'=>'created_at', 'filter'=> '' , 'editable'=> true, 'type'=>'timestamp'],
                                'updated_at' =>   ['name'=>'updated_at', 'filter'=> '' , 'editable'=> true, 'type'=>'timestamp'],
                                'id_user_create' => ['name'=>'id_user_create', 'filter'=> '' , 'editable'=> true,'type'=>'numeric'],
                                'id_user_update' => ['name'=>'id_user_update', 'filter'=> '' , 'editable'=> true,'type'=>'numeric'],
                              ];
     
    //mapeo de relaciones
    protected $relationship_map = [
                                    'vehiculo'=>[ 
                                                 'type'  =>'onetoone',
                                                 'foreign_controller' => 'App\Http\Controllers\TestMongo\VehiculoController' ,
                                                 'pivot_table'     => 'public.test_vehiculo',
                                                 'pivot_sequence'  => '',
                                                 'pivot_id'        => '',
                                                 'pivot_id_parent' => 'id_vehiculo',
                                                 'pivot_id_foreign'=> 'id_vehiculo',
                                                 'action'          => 'create'
                                               ],
                                    'conductor'=>[ 
                                                 'type'  =>'onetoone',
                                                 'foreign_controller' => 'App\Http\Controllers\TestMongo\ConductorController' ,
                                                 'pivot_table'     => 'public.test_conductor',
                                                 'pivot_sequence'  => '',
                                                 'pivot_id'        => '',
                                                 'pivot_id_parent' => 'id_conductor',
                                                 'pivot_id_foreign'=> 'id_conductor',
                                                 'action'          => 'create'
                                               ],
                                 ];
    
     
    //Asociaciones permitidas
    protected $whiteWith = ["vehiculo", "conductor"];
    
    protected $colums_identify = [];
    protected $colums_title =[];
    protected $excel_with_tables =[];
    protected $excel_change_data =[];
    
    
    
    
    /**
     *
     * @param unknown $query
     */
    public function scopeCustomWhere($query)
    {
    	return $query;
    }
   
    //RELACIONES
    
    public function vehiculo()
    {
    	return $this->belongsTo('App\models\TestMongo\Vehiculo', 'id_vehiculo', 'id_vehiculo');
    }
    
    public function conductor()
    {
    	return $this->belongsTo('App\models\TestMongo\Conductor', 'id_conductor', 'id_conductor');
    }
    
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
