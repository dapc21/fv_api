<?php
namespace App\Models\Devices;

use App\datatraffic\lib\Util;
use App\Http\Controllers\GenericMongo\CompanyScope;
use Jenssegers\Mongodb\Eloquent\Model as Moloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use App\Models\Generic\GenericModelTrait;

class DeviceInstance extends Moloquent
{
    use SoftDeletes;
    use GenericModelTrait;

    //Equibalente a tables en moloquent
    protected $collection = 'deviceInstances';
    //Para softdeleted
    protected $dates = ['deleted_at'];
    //Nombre de la columna que hace parte de llave primaria
    protected $primaryKey = '_id'; //No hace falta porque es embebido
    //Nombre de los campos calculados
    protected $appends = array();
    //Campos escondidos
    protected $hidden = [];
    //Protegidos
    protected $guarded = ['company','deviceDefinition','isUsed'];
    //Nombre de las columnas que se mostraran en la version simple de select y show
    protected $view = [];
    //mapeo de columnas(no se está usando)
    protected $validation_rules = [
        'id_deviceDefinition' => "required",
        'serial'  => "required|unique:deviceInstances,serial,NULL,_id,deleted_at,NULL|alpha_num",
        'customAttributes'  => "required",
    ];
    //mapeo de relaciones
    protected $relationship_map =
    [
        'company' =>
        [
            'type'  =>'onetoone',
            'foreign_controller' => 'App\Http\Controllers\Companies\CompanyController',
            'pivot_id_parent' => '_id',
            'pivot_id_foreign'=> 'id_company',
        ],
        'deviceDefinition' =>
        [
            'type'  =>'onetoone',
            'foreign_controller' => 'App\Http\Controllers\Devices\DeviceDefinitionController',
            'pivot_id_parent' => '_id',
            'pivot_id_foreign'=> 'id_deviceDefinition',
        ],
        'resourceInstance' =>
        [
            'type'  =>'onetoone',
            'foreign_controller' => 'App\Http\Controllers\Resources\ResourceInstanceController',
            'pivot_id_parent' => '_id',
            'pivot_id_foreign'=> 'id_resourceInstance',
        ]
    ];

    //Asociaciones permitidas
    protected $whiteWith = ['company', 'deviceDefinition'];
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
        return $this->belongsTo('App\Models\Companies\Company', 'id_company', '_id');
    }

    public function deviceDefinition()
    {
        return $this->belongsTo('App\Models\Devices\DeviceDefinition', 'id_deviceDefinition', '_id');
    }

    public function resourceInstance()
    {
        return $this->belongsTo('App\Models\Resources\ResourceInstance', 'id_resourceInstance', '_id');
    }

}
