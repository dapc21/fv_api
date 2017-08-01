<?php
namespace App\Models\Scheduling;

use App\datatraffic\lib\Util;
use App\Http\Controllers\GenericMongo\CompanyScope;
use App\Http\Controllers\GenericMongo\ResourceCreateScope;
use Jenssegers\Mongodb\Eloquent\Model as Moloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use App\Models\Generic\GenericModelTrait;

class Process extends Moloquent
{
    use SoftDeletes;
    use GenericModelTrait;

    //Equibalente a tables en moloquent
    protected $collection = 'processes';
    //Para softdeleted
    protected $dates = ['deleted_at','targetDate'];
    //Nombre de la columna que hace parte de llave primaria
    protected $primaryKey = '_id';
    //Nombre de los campos calculados
    protected $appends = array();
    //Campos escondidos
    protected $hidden = [];
    //Protegidos
    protected $guarded = [];
    //Utilizado para las relaciones ManyToMany
    public $bIsUnidirectional = true;
    //Nombre de las columnas que se mostraran en la version simple de select y show
    protected $view =[];
    //mapeo de columnas(no se está usando)
    protected $validation_rules = [];
    //mapeo de relaciones
    protected $relationship_map = [
        'company'=>
        [
            'type'  =>'onetoone',
            'foreign_controller' => 'App\Http\Controllers\Companies\CompanyController' ,
            'pivot_id_parent' => '_id',
            'pivot_id_foreign'=> 'id_company',
        ],
        'file' =>
        [
            'type' =>'embedsone',
            'foreign_controller' => 'App\Http\Controllers\Scheduling\FileController',
            'pivot_id_parent' => '_id',
            'pivot_id_foreign'=> 'id_file',
        ],
        'planningConfiguration' =>
        [
            'type' =>'embedsone',
            'foreign_controller' => 'App\Http\Controllers\Scheduling\PlanningConfigurationController',
            'pivot_id_parent' => '_id',
            'pivot_id_foreign'=> 'id_file',
        ],
        'statusConfigurations' =>
        [
            'type'  =>'embedsmany',
            'foreign_controller' => 'App\Http\Controllers\Scheduling\StatusConfigurationController',
            'pivot_id_parent' => '_id',
            'pivot_id_foreign'=> 'id_statusConfiguration',
        ],
        'actualStep' =>
        [
            'type' =>'embedsone',
            'foreign_controller' => 'App\Http\Controllers\Scheduling\StepController',
            'pivot_id_parent' => '_id',
            'pivot_id_foreign'=> 'id_step',
        ],
        'steps' =>
        [
            'type' =>'embedsmany',
            'foreign_controller' => 'App\Http\Controllers\Scheduling\StepController',
            'pivot_id_parent' => '_id',
            'pivot_id_foreign'=> 'id_step',
        ],
        'forms' =>
        [
            'type'  =>'manytomany',
            'foreign_controller' => 'App\Http\Controllers\Forms\FormController',
            'pivot_id_parent' => '_id',
            'pivot_id_foreign'=> 'id_form',
        ],
        'resourceGroups' =>
        [
            'type'  =>'manytomany',
            'foreign_controller' => 'App\Http\Controllers\Resources\ResourceGroupController',
            'pivot_id_parent' => '_id',
            'pivot_id_foreign'=> 'id_resourceGroup',
        ],
        'resourceDefinitions' =>
        [
            'type'  =>'manytomany',
            'foreign_controller' => 'App\Http\Controllers\Resources\ResourceDefinitionController',
            'pivot_id_parent' => '_id',
            'pivot_id_foreign'=> 'id_resourceDefinition',
        ],
        'resourceInstances' =>
        [
            'type'  =>'manytomany',
            'foreign_controller' => 'App\Http\Controllers\Resources\ResourceInstanceController',
            'pivot_id_parent' => '_id',
            'pivot_id_foreign'=> 'id_resourceInstance',
        ],
        'forms' =>
        [
            'type'  =>'manytomany',
            'foreign_controller' => 'App\Http\Controllers\Forms\FormController',
            'pivot_id_parent' => '_id',
            'pivot_id_foreign'=> 'id_form',
        ],
        'startEndLocations' =>
        [
            'type'  =>'embedsmany',
            'foreign_controller' => 'App\Http\Controllers\Scheduling\StartEndLocationController',
            'pivot_id_parent' => '_id',
            'pivot_id_foreign'=> 'id_startEndLocation',
        ],
        'user_create' =>
        [
            'type'  =>'onetoone',
            'foreign_controller' => 'App\Http\Controllers\Resources\ResourceInstanceController',
            'pivot_id_parent' => '_id',
            'pivot_id_foreign'=> 'id_user_create',
        ],
    ];
    //
    //Asociaciones permitidas
    protected $whiteWith = [];
    protected $colums_identify = [];
    protected $colums_title =[];
    protected $excel_with_tables =[];
    protected $excel_change_data =[];

    //Boot
    protected static function boot()
    {
        parent::boot();

        if(!Util::$manageAllCompanies) {
            static::addGlobalScope(new CompanyScope());
        }

        if(!Util::$manageAllCreated) {
            static::addGlobalScope(new ResourceCreateScope());
        }
    }

    //No se USA
    public function scopeCustomWhere($query)
    {
    	return $query;
    }

    //CAMPOS CALCULADOS
   
    //RELACIONES
    public function company()
    {
        return $this->belongsTo('App\Models\Companies\Company', 'id_company', '_id');
    }

    public function file()
    {
        return $this->embedsOne('App\Models\Scheduling\File');
    }

    public function planningConfiguration()
    {
        return $this->embedsOne('App\Models\Scheduling\PlanningConfiguration');
    }

    public function actualStep()
    {
        return $this->embedsOne('App\Models\Scheduling\Step');
    }

    public function steps()
    {
        return $this->embedsMany('App\Models\Scheduling\Step');
    }

    public function resourceGroups()
    {
        return $this->belongsToMany('App\Models\Resources\ResourceGroup', null, 'processes', 'resourceGroups');
    }

    public function resourceDefinitions()
    {
        return $this->belongsToMany('App\Models\Resources\ResourceDefinition', null, 'processes', 'resourceDefinitions');
    }

    public function resourceInstances()
    {
        return $this->belongsToMany('App\Models\Resources\ResourceInstance', null, 'processes', 'resourceInstances');
    }

    public function forms()
    {
        return $this->belongsToMany('App\Models\Forms\Form', null, 'processes', 'forms');
    }

    public function statusConfigurations()
    {
        return $this->belongsToMany('App\Models\Scheduling\StatusConfiguration', null, 'processes', 'statusConfigurations');
    }

    public function startEndLocations()
    {
        return $this->embedsMany('App\Models\Scheduling\StartEndLocation');
    }

    public function user_create()
    {
        return $this->belongsTo('App\Models\Resources\ResourceInstance', 'id_user_create', '_id');
    }
}
