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

class Empresa extends Moloquent{
    //use \Illuminate\Database\Eloquent\SoftDeletes;
    use SoftDeletes;
    use \App\models\generic\GenericModelTrait;
    
    protected $collection = 'empresas';  
    
    //Nombre de la tabla
    protected $table = 'public.test_empresa';
    //Nombre de la columna que hace parte de llave primaria
    //protected $primaryKey = 'id_empresa';
    //Nombre de los campos calculados
    protected $appends = array();
    
    //Nombre de las columnas que se mostraran en la version simple de select y show
    protected $view = [
	    		'list' => [
                            'id_empresa',
                            'nombre'
                        ],
	    		'simple' => [
                            'id_empresa',
                            'nombre',
                            'nit',
                            'telefono',
                            'fax',
                            'email',
                            'representante',
                            'eliminado'
                        ],
                'full' => [
                            'id_empresa',
                            'nombre',
                            'nit',
                            'telefono',
                            'fax',
                            'email',
                            'representante',
                            'logo',
                            'estilo',
                            'eliminado'
                        ]
    		];
    //mapeo de relaciones
    protected $columns_map = [
                    '_id'=>['name'=>'_id', 'filter'=> '' ,'type'=>'string'],
                    'id_empresa'=>['name'=>'id_empresa', 'filter'=> '' ,'type'=>'numeric'],
                    'nombre'=>['name'=>'nombre', 'filter'=> '' ,'type'=>'string'],
                    'deleted_at'=>['name'=>'deleted_at', 'filter'=> '' ,'type'=>'timestamp'],
                    'created_at'=>['name'=>'created_at', 'filter'=> '' ,'type'=>'timestamp'],
                    'updated_at'=>['name'=>'updated_at', 'filter'=> '' ,'type'=>'timestamp'],
                    'id_user_create'=>['name'=>'id_user_create', 'filter'=> '' ,'type'=>'numeric'],
                    'id_user_update'=>['name'=>'id_user_update', 'filter'=> '' ,'type'=>'numeric'],
   ];
    
    protected $relationship_map =[
                                    'vehiculos'=>[  'type'=>'onetomany',
                                                    'foreign_controller' => 'App\Http\Controllers\publico\VehiculoController' ,
                                                    'pivot_table' =>'public.test_vehiculo',
                                                    'pivot_sequence'=>'',
                                                    'pivot_id'=>'',
                                                    'pivot_id_parent'=>'id_empresa',
                                                    'pivot_id_foreign'=>'id_empresa'
                                               ],
                                    'conductores'=>[
                                                    'type' => 'onetomany',
                                                    'foreign_controller' => '\App\Http\Controllers\Test\ConductorController',
                                                    'pivot_id_parent'=>'id_empresa',
                                                    'pivot_id_foreign'=>'id_empresa',
                                    ]
                                 ];
    
    //Asociaciones permitidas
    protected $whiteWith = ["vehiculos", "conductores"];
    
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
    
    
    ///RELACIONES 
    /**
     * Define la relacion con vehiculos
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    /*public function vehiculos()
    {
    	return $this->hasMany('App\models\Test\Vehiculo', 'id_empresa', 'id_empresa');
    }*/
    public function vehiculos()
    {
    	return $this->hasMany('App\models\TestMongo\Vehiculo', 'id_empresa', 'id_empresa');
    }
    
    /**
     * Define la relacion con los conductores asociados
     * @return type \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function conductores()
    {
    	return $this->hasMany('App\models\TestMongo\Conductor', 'id_empresa', 'id_empresa');
    }
    
}   