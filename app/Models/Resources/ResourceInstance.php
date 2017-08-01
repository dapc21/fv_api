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

class ResourceInstance extends Moloquent implements AuthenticatableContract
{
    use Authenticatable;
    use SoftDeletes;
    use GenericModelTrait;

    //Equibalente a tables en moloquent
    protected $collection = 'resourceInstances';
    //Para softdeleted
    protected $dates = ['deleted_at','expire'];
    //Nombre de la columna que hace parte de llave primaria
    protected $primaryKey = '_id';
    //Nombre de los campos calculados
    protected $appends = array();
    //Campos escondidos
    protected $hidden = ['password'];
    //Protegidos
    protected $guarded = ['company','login','resourceDefinition'];
    //Utilizado para las relaciones ManyToMany
    public $bIsUnidirectional = true;    
    //Nombre de las columnas que se mostraran en la version simple de select y show
    protected $view =[];
    //mapeo de columnas(no se está usando)
    protected $validation_rules = [
        "login" => "required|unique:resourceInstances,login,NULL,_id,deleted_at,NULL|max:50|alpha_num",
        "password" => "required|string",
        "status" => "required|in:active,inactive",
        "roles" => "required",
        "id_company" => "required",
    ];
    //mapeo de relaciones
    protected $relationship_map = [
        'company'=>[
            'type'  =>'onetoone',
            'foreign_controller' => 'App\Http\Controllers\Companies\CompanyController' ,
            'pivot_id_parent' => '_id',
            'pivot_id_foreign'=> 'id_company',
        ],       
        'resourceDefinition'=>[
            'type'  =>'onetoone',
            'foreign_controller' => 'App\Http\Controllers\Resources\ResourceDefinitionController' ,
            'pivot_id_parent' => '_id',
            'pivot_id_foreign'=> 'id_resourceDefinition',
        ],
        'resourceGroups' =>
        [
            'type'  =>'manytomany',
            'foreign_controller' => 'App\Http\Controllers\Resources\ResourceGroupController',
            'pivot_id_parent' => '_id',
            'pivot_id_foreign'=> 'id_resourceGroup',
        ],
        'deviceInstances' =>
        [
            'type'  =>'manytomany',
            'foreign_controller' => 'App\Http\Controllers\Devices\DeviceInstanceController',
            'pivot_id_parent' => '_id',
            'pivot_id_foreign'=> 'id_deviceInstance',
        ],
        'customAttributes' =>
        [
            'type' =>'embedsone',
            'foreign_controller' => 'App\Http\Controllers\Resources\CustomAttributeController',
            'pivot_id_parent' => '_id',
            'pivot_id_foreign'=> 'id_customAttribute',
        ],
        'forms' =>
        [
            'type'  =>'manytomany',
            'foreign_controller' => 'App\Http\Controllers\Campaign\FormController',
            'pivot_id_parent' => '_id',
            'pivot_id_foreign'=> 'id_form',
        ],
        'pivotRoles' =>
        [
            'type'  =>'embedsmany',
            'foreign_controller' => 'App\Http\Controllers\Users\PivotRoleController',
            'pivot_id_parent' => '_id',
            'pivot_id_foreign'=> 'id_role',
        ],
        'roles' =>
        [
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

    //Boot
    protected static function boot()
    {
        parent::boot();

        if(!Util::$manageAllCompanies) {
            static::addGlobalScope(new CompanyScope());
        }
    }

    //No se USA
    public function scopeCustomWhere($query)
    {
    	return $query;
    }

    //Reglas de validacion
    public function getValidationRules($method, $id_company)
    {
        if(is_null($id_company)){
            $id_company = (string)$this->id_company['$id'];
        }

        $validation_rules = $this->validation_rules;
        switch($method)
        {
            case 'CREATE':
                $validation_rules["login"] = "required|unique:resourceInstances,login,NULL,_id,deleted_at,NULL|max:50|alpha_num";
                break;
            case 'UPDATE':
                $validation_rules["login"] = "required|unique:resourceInstances,login,".$this->_id.",_id,deleted_at,NULL|max:50|alpha_num";
                break;
        }
        return $validation_rules;
    }

    //RELACIONES
    public function company()
    {
        return $this->belongsTo('App\Models\Companies\Company', 'id_company', '_id');
    }
    
    public function resourceDefinition()
    {
        return $this->belongsTo('App\Models\Resources\ResourceDefinition', 'id_resourceDefinition', '_id');
    }

    public function resourceGroups()
    {
        return $this->belongsToMany('App\Models\Resources\ResourceGroup', null, 'resourceInstances', 'resourceGroups');
    }

    public function deviceInstances()
    {
        return $this->belongsToMany('App\Models\Devices\DeviceInstance', null, 'resourceInstances', 'deviceInstances');
    }

    public function customAttributes()
    {
        return $this->embedsOne('App\Models\Resources\CustomAttribute');
    }

    public function pivotRoles() {
        return $this->embedsMany('App\Models\Users\PivotRole');
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
        	if ( strcmp($userPivotRole['applicationName'], $application) == 0 ){
            	$idRoles[] = $userPivotRole['id_role']['$id'];
        	}
        }
        
        $modules = [];
        $criteria = ['$and' =>[['_id' => ['$in' => $idRoles]],['application.name' => $application]]];
        $roles = DB::collection('roles')->whereRaw($criteria)->project(['application.modules' => 1])->get();
        if($roles) {
        	foreach ($roles as $role) {
        		foreach ($role['application']['modules'] as $app)
        		{
        			$modules[$app['name']] = $app;
        			
        		}
        	}
        }
        // extrae los modulos que aplica a la licencia
        $company = $this->company;
        $licences= $company->licenses;
        $modulesLic=[];
        if( $licences ){
        	foreach ($licences as $app ){
        		if ( strcmp($app['application']['name'], $application) == 0 ){
        			foreach ( $app['application']['modules'] as $moduleL)
        			$modulesLic[] = $moduleL;
        		}
        	}
        }
        //cruza los modulos de la licencia con los propios del recurso
        $result=[];
        foreach ($modulesLic as $modulelic ) {
        	if( key_exists( $modulelic['name'] , $modules) ){
        		$result[]=$modules[ $modulelic['name'] ];
        	}
        }
        return $result;
    }

    public function getRoutingTool(){
        //Verificar la herramienta de routing
        $resourceDefinition = (new ResourceDefinition())->newQueryWithoutScopes()->where('_id','=',$this->id_resourceDefinition['$id'])->first();
        $routingTool = $resourceDefinition->routingTool;

        return $routingTool;
    }
}
