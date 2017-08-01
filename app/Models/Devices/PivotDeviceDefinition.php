<?php
namespace App\Models\Devices;

use App\datatraffic\lib\Util;
use App\Http\Controllers\GenericMongo\CompanyScope;
use Jenssegers\Mongodb\Eloquent\Model as Moloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use App\Models\Generic\GenericModelTrait;

class PivotDeviceDefinition extends Moloquent
{
    use SoftDeletes;
    use GenericModelTrait;

    //Equibalente a tables en moloquent
    protected $collection = 'pivotDeviceDefinitions';
    //Para softdeleted
    protected $dates = ['deleted_at'];
    //Nombre de la columna que hace parte de llave primaria
    protected $primaryKey = '_id'; //No hace falta porque es embebido
    //Nombre de los campos calculados
    protected $appends = array();
    //Campos escondidos
    protected $hidden = [];
    //Protegidos
    protected $guarded = [];
    //Nombre de las columnas que se mostraran en la version simple de select y show
    protected $view = [];
    //mapeo de columnas(no se está usando)
    protected $validation_rules = [];
    //mapeo de relaciones
    protected $relationship_map = [
        'deviceDefinition' =>
            [
                'type'  =>'onetoone',
                'foreign_controller' => 'App\Http\Controllers\Devices\DeviceDefinitionController',
                'pivot_id_parent' => '_id',
                'pivot_id_foreign'=> 'id_deviceDefinition',
            ],
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
    public function deviceDefinition()
    {
        return $this->belongsTo('App\Models\Devices\DeviceDefinition', 'id_deviceDefinition', '_id');
    }
}
