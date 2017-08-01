<?php
namespace App\Models\Tracking;

use Jenssegers\Mongodb\Eloquent\Model as Moloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use App\Models\Generic\GenericModelTrait;

class Event extends Moloquent
{
    use SoftDeletes;    
    use GenericModelTrait;
    
    //Equibalente a tables en moloquent
    protected $collection = 'events';
    //Para softdeleted
    protected $dates = ['deleted_at'];
    //Nombre de la columna que hace parte de llave primaria
    protected $primaryKey = '_id';
    //Nombre de los campos calculados
    protected $appends = array();
    //Nombre de las columnas que se mostraran en la version simple de select y show
    protected $view = [];
    //mapeo de columnas(no se está usando)
    protected $validation_rules = [];
    //mapeo de relaciones
    protected $relationship_map = [
        'resourceInstance' =>
            [
                'type' =>'embedsone',
                'foreign_controller' => 'App\Http\Controllers\Resources\ResourceInstanceController',
                'pivot_id_parent' => '_id',
                'pivot_id_foreign'=> 'id_resource',
            ],
    	'task' =>
    		[
    				'type' =>'embedsone',
    				'foreign_controller' => 'App\Http\Controllers\Planning\TaskController',
    				'pivot_id_parent' => '_id',
    				'pivot_id_foreign'=> 'id_task',
    		],
    	'company' =>
    		[
    				'type'  =>'onetoone',
    				'foreign_controller' => 'App\Http\Controllers\Companies\CompanyController',
    				'pivot_id_parent' => '_id',
    				'pivot_id_foreign'=> 'id_company',
    		]
    ];
    
    //Asociaciones permitidas
    protected $whiteWith = [];
    protected $colums_identify = [];
    protected $colums_title =[];
    protected $excel_with_tables =[];
    protected $excel_change_data =[];
    
    //No se USA
    public function scopeCustomWhere($query)
    {
    	return $query;
    }
   
    //RELACIONES
    
    /**
     * Relacion con el recurso embebido
     * @return \Jenssegers\Mongodb\Relations\EmbedsOne
     */
    public function resourceInstance()
    {
        return $this->embedsOne('App\Models\Resources\ResourceInstance');
    }
    
    /**
     * relacion con la tarea embebida
     * @return \Jenssegers\Mongodb\Relations\EmbedsOne
     */
    public function task()
    {
    	return $this->embedsOne('App\Models\Planning\Task');
    }
    
    /**
     * Relacion con las empresas 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
    	return $this->belongsTo('App\Models\Companies\Company', 'id_company', '_id');
    }
}
