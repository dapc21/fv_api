<?php
namespace App\Models\Resources;

use App\datatraffic\lib\Util;
use App\Http\Controllers\GenericMongo\CompanyScope;
use Jenssegers\Mongodb\Eloquent\Model as Moloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use App\Models\Generic\GenericModelTrait;

class ResourceGroup extends Moloquent
{
    use SoftDeletes;
    use GenericModelTrait;

    //Equibalente a tables en moloquent
    protected $collection = 'resourceGroups';
    //Para softdeleted
    protected $dates = ['deleted_at'];
    //Nombre de la columna que hace parte de llave primaria
    protected $primaryKey = '_id';
    //Nombre de los campos calculados
    protected $appends = array();
    //Campos escondidos
    protected $hidden = [];
    //Protegidos
    protected $guarded = ['company','resourceDefinition'];
    //Utilizado para las relaciones ManyToMany
    public $bIsUnidirectional = false;    
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
        'resourceDefinition'=>[
            'type'  =>'onetoone',
            'foreign_controller' => 'App\Http\Controllers\Resources\ResourceDefinitionController' ,
            'pivot_id_parent' => '_id',
            'pivot_id_foreign'=> 'id_resourceDefinition',
        ],
        'speedLimit'=>[
            'type'  =>'onetoone',
            'foreign_controller' => 'App\Http\Controllers\Controls\SpeedLimitController' ,
            'pivot_id_parent' => '_id',
            'pivot_id_foreign'=> 'id_speedLimit',
        ],
        'geofences' => [
                'type'  =>'manytomany',
                'foreign_controller' => 'App\Http\Controllers\Controls\GeofenceController',
                'pivot_id_parent' => '_id',
                'pivot_id_foreign'=> 'id_geofence',
        ],
    ];

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
    }
	
    //No se USA
    public function scopeCustomWhere($query)
    {
    	return $query;
    }

    //RELACIONES
    public function company()
    {
        return $this->belongsTo('App\Models\Companies\Companies\Company', 'id_company', '_id');
    }

    public function deviceDefinition()
    {
        return $this->belongsTo('App\Models\Devices\DeviceDefinition', 'id_resourceDefinition', '_id');
    }

    public function speedLimit()
    {
        return $this->belongsTo('App\Models\Controls\SpeedLimit', 'id_speedLimit', '_id');
    }

    public function geofences()
    {
        return $this->belongsToMany('App\Models\Controls\Geofence', null, 'resourceGroups', 'geofences');
    }
}