<?php
namespace App\Models\Resources;

use App\datatraffic\lib\Util;
use App\Http\Controllers\GenericMongo\CompanyScope;
use Jenssegers\Mongodb\Eloquent\Model as Moloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use App\Models\Generic\GenericModelTrait;
use DB;

class ResourceTracking extends ResourceInstance
{
    use Authenticatable;
    //use SoftDeletes;
    use GenericModelTrait;

    //Equibalente a tables en moloquent
    protected $collection = 'resourceTracking';
    //Para softdeleted
    protected $dates = ['deleted_at'];
    //Nombre de la columna que hace parte de llave primaria
    protected $primaryKey = '_id';
    //Nombre de los campos calculados
    protected $appends = array();
    //Campos escondidos
    protected $hidden = [];
    //Protegidos
    protected $guarded = ['company','login'];
    //Utilizado para las relaciones ManyToMany
    public $bIsUnidirectional = true;
    //Nombre de las columnas que se mostraran en la version simple de select y show
    protected $view =[];
    //mapeo de columnas(no se está usando)
    protected $validation_rules = [];
    //mapeo de relaciones
    protected $relationship_map = [
        'company'=>[
            'type'  =>'onetoone',
            'foreign_controller' => 'App\Http\Controllers\Companies\CompanyController' ,
            'pivot_id_parent' => '_id',
            'pivot_id_foreign'=> 'id_company',
        ],
        'roles' => [
                'type'  =>'embedsmany',
                'foreign_controller' => 'App\Http\Controllers\Users\PivotRoleController',
                'pivot_id_parent' => '_id',
                'pivot_id_foreign'=> 'id_role',
        ],
    ];

    //Asociaciones permitidas
    protected $whiteWith = ['company','resourceDefinition','resourceGroups','deviceInstances'];
    protected $colums_identify = [];
    protected $colums_title =['login', 'email'];
    protected $excel_with_tables =[];
    protected $excel_change_data =[];

    //No se USA
    public function scopeCustomWhere($query)
    {
        return $query;
    }

    //RELACIONES
    public function company()
    {
        return $this->belongsTo('App\Models\Companies\Company', 'id_company', '_id');
    }

    public function roles() {
        return $this->embedsMany('App\Models\Users\PivotRole');
    }

    //OTROS METODOS
    public function getApplicationModules($application){
        //Obtener modulos a los que tiene acceso el usuario
        $idRoles = [];
        $userPivotRoles = $this->roles;
        foreach ($userPivotRoles as $userPivotRole)
        {
            $idRoles[] = $userPivotRole['id_role']['$id'];
        }
        $modules = [];
        $criteria = ['$and' =>[['_id' => ['$in' => $idRoles]],['application.name' => $application]]];
        $roles = DB::collection('roles')->whereRaw($criteria)->project(['application.modules' => 1])->get();
        if($roles) {
            foreach ($roles as $role) {
                foreach ($role['application']['modules'] as $app)
                {
                    $modules[] = $app;
                }
            }
        }

        return $modules;
    }

    public function getRoutingTool(){
        return null;
    }
}
