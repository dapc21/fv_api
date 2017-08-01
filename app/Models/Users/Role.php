<?php
namespace App\Models\Users;

use Jenssegers\Mongodb\Eloquent\Model as Moloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use App\datatraffic\lib\Util;
use App\Http\Controllers\GenericMongo\RoleScope;

class Role extends Moloquent 
{
    use SoftDeletes;    
    use \App\Models\Generic\GenericModelTrait;
    
    //Equibalente a tables en moloquent
    protected $collection = 'roles';
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
        "name" => "required",
        "application" => "required"
    ];
    //mapeo de relaciones
    protected $relationship_map =
    [
        'application' => 
        [ 
            'type'  =>'embedsone',
            'foreign_controller' => 'App\Http\Controllers\Applications\ApplicationController',
            'pivot_id_parent' => '_id',
            'pivot_id_foreign'=> 'id_application',
        ]
    ];
    
    //Asociaciones permitidas
    protected $whiteWith = ['application'];
    
    protected $colums_identify = [];
    protected $colums_title =[];
    protected $excel_with_tables =[];
    protected $excel_change_data =[];
    
    
    protected static function boot()
    {
    	parent::boot();    
    	if(!Util::$manageSystemRoles) {
    		static::addGlobalScope(new RoleScope());
    	}
    }
    
    /**
     *
     * @param unknown $query
     */
    public function scopeCustomWhere($query)
    {
    	return $query;
    }
   
    //RELACIONES
    public function application() 
    {
        return $this->embedsOne('App\Models\Applications\Application');
    }
}
