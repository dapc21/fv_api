<?php
namespace App\Models\Tracking;

use App\Http\Controllers\GenericMongo\CompanyScope;
use App\Http\Controllers\GenericMongo\ResourceCompanyScope;
use App\datatraffic\lib\Util;
use Jenssegers\Mongodb\Eloquent\Model as Moloquent;
use App\Models\Generic\GenericModelTrait;

class History extends Moloquent
{
    use GenericModelTrait;
    
    //Equibalente a tables en moloquent
    protected $collection = 'history';
    //Para softdeleted
    protected $dates = ['updateTime','tsLastVisiblePosicion'];
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
        'company'=>[
            'type'  =>'onetoone',
            'foreign_controller' => 'App\Http\Controllers\Companies\CompanyController' ,
            'pivot_id_parent' => '_id',
            'pivot_id_foreign'=> 'id_company',
        ],
        'resourceInstance' =>
        [
            'type' =>'embedsone',
            'foreign_controller' => 'App\Http\Controllers\Resources\ResourceInstanceController',
            'pivot_id_parent' => '_id',
            'pivot_id_foreign'=> 'id_resource',
        ],
        'actualGeofences' =>
        [
            'type'  =>'embedsmany',
            'foreign_controller' => 'App\Http\Controllers\Controls\GeofenceController',
            'pivot_id_parent' => '_id',
            'pivot_id_foreign'=> 'id_geofence',
        ],
        'actualCheckPoints' =>
        [
            'type'  =>'embedsmany',
            'foreign_controller' => 'App\Http\Controllers\Controls\CheckPointController',
            'pivot_id_parent' => '_id',
            'pivot_id_foreign'=> 'id_checkPoint',
        ],
        'deviceData' =>
        [
            'type'  =>'embedsone',
            'foreign_controller' => 'App\Http\Controllers\Tracking\DeviceDataController',
            'pivot_id_parent' => '_id',
            'pivot_id_foreign'=> 'id_deviceData',
        ],
        'tasks' =>
        [
            'type'  =>'embedsmany',
            'foreign_controller' => 'App\Http\Controllers\Planning\TaskController',
            'pivot_id_parent' => '_id',
            'pivot_id_foreign'=> 'id_task',
        ],
        'icon' =>[
            'type'  =>'embedsone',
            'foreign_controller' => 'App\Http\Controllers\Icons\IconController',
            'pivot_id_parent' => '_id',
            'pivot_id_foreign'=> 'id_icon',
        ],
    	'events' =>		[
    				'type'  =>'embedsmany',
    				'foreign_controller' => 'App\Http\Controllers\Tracking\EventController',
    				'pivot_id_parent' => '_id',
    				'pivot_id_foreign'=> 'id_event',
    		],
    ];
    
    //Asociaciones permitidas
    protected $whiteWith = ['modules'];
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

        if(!Util::$manageAllResource) {
            static::addGlobalScope(new ResourceCompanyScope());
        }
    }

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
    
    public function resourceInstance()
    {
        return $this->embedsOne('App\Models\Resources\ResourceInstance');
    }

    public function actualGeofences()
    {
        return $this->embedsMany('App\Models\Controls\Geofence');
    }

    public function actualCheckPoints()
    {
        return $this->embedsMany('App\Models\Controls\CheckPoint');
    }

    public function deviceData()
    {
        return $this->embedsOne('App\Models\Tracking\DeviceData');
    }

    public function tasks()
    {
        return $this->embedsMany('App\Models\Planning\Task');
    }

    public function icon()
    {
        return $this->embedsOne('App\Models\Icons\Icon');
    }
    
    /**
     * mapea los querys
     * @return \Jenssegers\Mongodb\Relations\EmbedsMany
     */
    public function events(){
    	return $this->embedsMany('App\Models\Tracking\Event');
    }
}
