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

class Vehiculo extends Moloquent {
    use SoftDeletes;    
    use \App\models\generic\GenericModelTrait;
    
    protected $collection = 'vehiculos';
    //estas dos son equivalente, con más prioridad collections
    protected $table = 'public.test_vehiculo';
    //Para softdeleted
    protected $dates = ['deleted_at'];
    
    //Nombre de la columna que hace parte de llave primaria
    //protected $primaryKey = 'id_vehiculo';
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
                            'empresa'
                        ]
    		];
    //mapeo de columnas
    protected $columns_map = [
                                '_id' => ['name'=>'_id', 'filter'=> '' , 'editable'=> true, 'type'=>'numeric'],
                                //'id_vehiculo' => ['name'=>'id_vehiculo', 'filter'=> '' , 'editable'=> true, 'type'=>'numeric'],
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
                                                 'pivot_id_parent' => '_id',
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
                                                 'pivot_id_foreign'=> 'dispositivo',
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
                                    'conductores2' => [ 
                                                 'type'  =>'embmanytomany',
                                                 'foreign_controller' => 'App\Http\Controllers\TestMongo\ConductorController',
                                                 'pivot_controller' => 'App\Http\Controllers\TestMongo\PivotConductoresController',
                                                 'pivot_table'     => 'public.test_pivot_conductores',
                                                 'pivot_sequence'  => 'public.seq_test_vehiculo_conductor_id',
                                                 'pivot_id'        => 'id_vehiculo_conductor',
                                                 'pivot_id_parent' => 'id_vehiculo',
                                                 'pivot_id_foreign'=> 'id_conductor',
                                                 'action'          => 'create'
                                               ]
                                 ];
    
     
    //Asociaciones permitidas
    protected $whiteWith = ["empresa" , "dispositivos" , "conductores", "conductores2" ];
    
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
    /**
     * Define la relacion pertenece con la empresa a la que pertenece
     */
    /*public function empresa()
    {
    	return $this->belongsTo('App\models\Test\Empresa', 'id_empresa', 'id_empresa');
    }*/
    
    /**
     *  relaciona los dispositivos relacionados con el vehiculo
     * @return type
     */
    /*public function dispositivos()
    {
    	return $this->hasMany('App\models\Test\Dispositivo', 'id_vehiculo', 'id_vehiculo');
    }*/
    
    /**
     * Define la relacion con los conductores
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    /*public function conductores()
    {
        return $this->belongsToMany('App\models\Test\Conductor', "public.test_conductor_vehiculo", 'id_vehiculo', 'id_conductor')
                    ->whereNull('public.test_conductor_vehiculo.deleted_at') // Table `group_user` has column `deleted_at`
                    ->withPivot('dias','hora_inicio','hora_fin')
                    ->withTimestamps(); // Table `group_user` has columns: `created_at`, `updated_at`;
    }*/
    public function empresa()
    {
    	return $this->belongsTo('App\models\TestMongo\Empresa', 'id_empresa', '_id');
    }
    
    public function conductores() {
        return $this->belongsToMany('App\models\TestMongo\Conductor', null, 'vehiculos', 'conductores');
    }
    
    public function conductores2() {
        return $this->embedsMany('App\models\TestMongo\PivotVehiculoConductor'/*, 'dispositivos', 'dispositivos'*/);
    }
    
    public function dispositivos() {
        return $this->embedsMany('App\models\TestMongo\Dispositivo'/*, 'dispositivos', 'dispositivos'*/);
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
