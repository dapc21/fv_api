<?php
namespace App\Models\TestMongo;

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use App\datatraffic\lib\Util;
use datatraffic\lib\Configuration;
use Illuminate\Support\Facades\DB;

use Jenssegers\Mongodb\Model as Moloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Conductor extends Moloquent{
    //use \Illuminate\Database\Eloquent\SoftDeletes;
    use SoftDeletes;
    use \App\models\generic\GenericModelTrait;
    
    protected $collection = 'conductores';
    
    /**
     * Nombre de la tabla
     * @var String
     */
    protected $table = 'public.test_conductor';
    
    /**
     * Nombre de la columna que hace parte de llave primaria
     * @var String 
     */
    //protected $primaryKey = 'id_conductor';
    
    /**
     * Nombre de los campos calculados
     * @var Array
     */
    protected $appends = array();
    
    /**
     * Nombre de las columnas que se mostraran en la version simple de select y show
     * @var Array 
     */
    protected $view = [
	    		'list' => [
                            'id_conductor',
                            'num_documento',
                        ],
	    		'simple' => [
                            'id_conductor',
                            'nombres',
                            'apellidos',
                            'num_documento'
                        ],
                'full' => [
                    'id_conductor',
                    'nombres',
                    'apellidos',
                    'num_documento',
                    'celular',
                    'id_empresa'
                ]
    		]; 
    /**
     * Mapeo de columnas
     * @var Array 
     */
    protected $columns_map = [
        '_id' => ['name'=>'_id', 'filter'=> '' ,'type'=>'numeric'],
                                'id_conductor' => ['name'=>'id_conductor', 'filter'=> '' ,'type'=>'numeric'],
                                'nombres' => ['name'=>'nombres', 'filter'=> '' ,'type'=>'string'],
                                'apellidos' => ['name'=>'apellidos', 'filter'=> '' ,'type'=>'string'],
                                'num_documento' => ['name'=>'num_documento', 'filter'=> '' ,'type'=>'string'],
                                'celular' => ['name'=>'celular', 'filter'=> '' ,'type'=>'string'],
                                'id_empresa' => ['name'=>'id_empresa', 'filter'=> '' ,'type'=>'numeric'],
                                'deleted_at' => ['name'=>'deleted_at', 'filter'=> '' ,'type'=>'timestamp'],
                                'created_at' => ['name'=>'created_at', 'filter'=> '' ,'type'=>'timestamp'],
                                'updated_at' => ['name'=>'updated_at', 'filter'=> '' ,'type'=>'timestamp'],
                                'id_user_create' => ['name'=>'id_user_create', 'filter'=> '' ,'type'=>'numeric'],
                                'id_user_update' => ['name'=>'id_user_update', 'filter'=> '' ,'type'=>'numeric'],
                              ];
    /**
      * mapeo de relaciones
      * @var Array 
     */
    protected $relationship_map =[
                                'conductores' =>[
                                                'type'  => 'manytomany',
                                                'foreign_controller' => 'App\Http\Controllers\TestMongo\ConductorVehiculo',
                                                'pivot_table'      => 'public.test_vehiculo_conductor',
                                                'pivot_sequence'   => 'public.seq_test_vehiculo_conductor_id',
                                                'pivot_id' => 'id_vehiculo_conductor',
                                                'pivot_id_parent'  => 'id_conductor',
                                                'pivot_id_foreign' => 'id_vehiculo'
                                              ],
                                'empresa'=>   [ 
                                                'type'  =>'onetoone',
                                                'foreign_controller' => 'App\Http\Controllers\TestMongo\EmpresaController' ,
                                                'pivot_table'     => 'public.test_empresa',
                                                'pivot_sequence'  => '',
                                                'pivot_id'        => '',
                                                'pivot_id_parent' => '_id',
                                                'pivot_id_foreign'=> 'id_empresa'
                                               ],
                ];
    
    /**
     * Asociaciones permitidas
     * @var Array
     */
    protected $whiteWith = ["empresa"];
    
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
    public function empresa()
    {
    	return $this->belongsTo('App\models\TestMongo\Empresa', 'id_empresa', '_id');
    }
    
    /**
     * Define la relacion con vehiculos
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function vehiculos()
    {
        return $this->belongsToMany('App\models\Test\Vehiculo', "public.test_conductor_vehiculo", 'id_conductor', 'id_vehiculo')
                    ->whereNull('public.test_conductor_vehiculo.deleted_at') // Table `group_user` has column `deleted_at`
                    ->withPivot('dias','hora_inicio','hora_fin')
                    ->withTimestamps(); // Table `group_user` has columns: `created_at`, `updated_at`;
    }
}