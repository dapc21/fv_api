<?php
namespace App\Models\Users;

use Jenssegers\Mongodb\Eloquent\Model as Moloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class PivotRole extends Moloquent
{
    use SoftDeletes;
    use \App\Models\Generic\GenericModelTrait;
    
    //Equibalente a tables en moloquent
    protected $collection = 'pivotroles';
    //Para softdeleted
    protected $dates = ['deleted_at'];
    //Nombre de la columna que hace parte de llave primaria
    protected $primaryKey = '_id';
    //Nombre de los campos calculados
    protected $appends = array();
    //Nombre de las columnas que se mostraran en la version simple de select y show
    protected $view = [];
    //Reglas de validacion
    protected $validation_rules = [
        "id_role" => "required",
        "applicationName" => "required|string",
        "roleName" => "required|string",
    ];
    //mapeo de relaciones
    protected $relationship_map =
    [
        'role' => [
            'type'  =>'onetoone',
            'foreign_controller' => 'App\Http\Controllers\Users\RoleController',
            'pivot_id_parent' => '_id',
            'pivot_id_foreign'=> 'id_role',
        ]
    ];
    
    //Asociaciones permitidas
    protected $whiteWith = ['application'];
    
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
    public function role()
    {
        return $this->belongsTo('App\Models\Users\Role','id_role','_id');
    }
}
