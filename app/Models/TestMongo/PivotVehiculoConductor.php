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

class PivotVehiculoConductor extends Moloquent {
    //use SoftDeletes;
    use \App\models\generic\GenericModelTrait;
    
    //Nombre de la tabla
    protected $table = 'pivot_vehiculos_conductores';
    //Nombre de la columna que hace parte de llave primaria
    //protected $primaryKey = 'id_vehiculo_conductor';
    //Nombre de los campos calculados
    protected $appends = array();
    
    //Nombre de las columnas que se mostraran en la version simple de select y show
    protected $view = [
	    		'list' => [
                            'dias',
                            'hora_inicio',
                            'hora_fin'
                        ],
	    		'simple' => [
                            'id_dispositivo',
                            'imei',
                        ],
                        'full' => [
                            'id_dispositivo',
                            'imei',
                            'id_vehiculo'
                        ]
    		      ];
    
    //mapeo de columnas
    protected $columns_map = [
                                'id_vehiculo_conductor'=>['name'=>'id_vehiculo_conductor', 'filter'=> '' , 'editable'=> true, 'type'=>'numeric'],
                                'id_conductor'=>['name'=>'id_conductor', 'filter'=> '' , 'editable'=> true, 'type'=>'numeric'],
                                'dias'=>['name'=>'dias', 'filter'=> '' , 'editable'=> true, 'type'=>'string'],
                                'hora_inicio'=>['name'=>'hora_inicio', 'filter'=> '' , 'editable'=> true, 'type'=>'string'],
                                'hora_fin'=>['name'=>'hora_fin', 'filter'=> '' , 'editable'=> true, 'type'=>'string'],
                                'deleted_at'=>['name'=>'deleted_at', 'filter'=> '' , 'editable'=> true, 'type'=>'timestamp'],
                                'created_at'=>['name'=>'created_at', 'filter'=> '' , 'editable'=> true, 'type'=>'timestamp'],
                                'updated_at'=>['name'=>'updated_at', 'filter'=> '' , 'editable'=> true, 'type'=>'timestamp'],
                                'id_user_create'=>['name'=>'id_user_create', 'filter'=> '' , 'editable'=> true, 'type'=>'numeric'],
                                'id_user_update'=>['name'=>'id_user_update', 'filter'=> '' , 'editable'=> true,'type'=>'numeric'],
                              ];
    
    //mapeo de relaciones
    protected $relationship_map =[
                                    'conductor'=>[ 
                                                 'type'  =>'onetoone',
                                                 'foreign_controller' => 'App\Http\Controllers\TestMongo\ConductorController' ,
                                                 'pivot_table'     => 'public.test_conductor',
                                                 'pivot_sequence'  => '',
                                                 'pivot_id'        => '',
                                                 'pivot_id_parent' => 'id_conductor',
                                                 'pivot_id_foreign'=> 'id_conductor',
                                                 'action'          => 'create'
                                                ]
                                 ];
    
     

    
    //Asociaciones permitidas
    protected $whiteWith = ["conductor"];
    
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
    public function conductor()
    {
    	return $this->belongsTo('App\models\TestMongo\Conductor', 'id_conductor', 'id_conductor');
    }
}