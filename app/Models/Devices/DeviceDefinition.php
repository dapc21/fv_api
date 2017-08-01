<?php
namespace App\Models\Devices;

use Jenssegers\Mongodb\Eloquent\Model as Moloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use App\Models\Generic\GenericModelTrait;

class DeviceDefinition extends Moloquent
{
    use SoftDeletes;
    use GenericModelTrait;

    //Equibalente a tables en moloquent
    protected $collection = 'deviceDefinitions';
    //Para softdeleted
    protected $dates = ['deleted_at'];
    //Nombre de la columna que hace parte de llave primaria
    protected $primaryKey = '_id'; //No hace falta porque es embebido
    //Nombre de los campos calculados
    protected $appends = array();
    //Utilizado para las relaciones ManyToMany
    public $bIsUnidirectional = true;
    //Nombre de las columnas que se mostraran en la version simple de select y show
    protected $view = [];
    //mapeo de columnas(no se está usando)
    protected $validation_rules = [];
    //mapeo de relaciones
    protected $relationship_map =
    [
        'parents' =>
        [
            'type'  =>'manytomany',
            'foreign_controller' => 'App\Http\Controllers\Devices\DeviceDefinitionController',
            'pivot_id_parent' => '_id',
            'pivot_id_foreign'=> 'id_deviceDefinition',
        ],
        'children' =>
        [
            'type'  =>'manytomany',
            'foreign_controller' => 'App\Http\Controllers\Devices\DeviceDefinitionController',
            'pivot_id_parent' => '_id',
            'pivot_id_foreign'=> 'id_deviceDefinition',
        ],
    ];

    //Asociaciones permitidas
    protected $whiteWith = ['parents', 'children', 'configurations'];
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
    public function parents()
    {
        return $this->belongsToMany('App\Models\Devices\DeviceDefinition', null, 'deviceDefinitions', 'parents');
    }

    public function children()
    {
        return $this->belongsToMany('App\Models\Devices\DeviceDefinition', null, 'deviceDefinitions', 'children');
    }
}
