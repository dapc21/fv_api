<?php
namespace App\Models\Resources;

use Jenssegers\Mongodb\Eloquent\Model as Moloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use App\Models\Generic\GenericModelTrait;

class ResourceTemplate extends Moloquent
{
    use SoftDeletes;   
    use GenericModelTrait;
    
    //Equibalente a tables en moloquent
    protected $collection = 'resourceTemplates';
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
    //Reglas de validacion
    protected $validation_rules = [];
    //mapeo de relaciones
    protected $relationship_map =
    [
        'customAttributes' => 
        [ 
            'type'  =>'embedsmany',
            'foreign_controller' => 'App\Http\Controllers\Devices\CustomAttributeController',
            'pivot_id_parent' => '_id',
            'pivot_id_foreign'=> 'id_device_definition',
        ],
        'deviceDefinitions' =>
        [
            'type'  =>'embedsmany',
            'foreign_controller' => 'App\Http\Controllers\Devices\PivotDeviceDefinitionController',
            'pivot_id_parent' => '_id',
            'pivot_id_foreign'=> 'id_deviceDefinition',
        ],
    ];
    
    //Asociaciones permitidas
    protected $whiteWith = ['customAttributes', 'deviceDefinitions'];
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
    public function customAttributes()
    {
        return $this->embedsMany('App\Models\Devices\CustomAttribute');
    }
    
    public function deviceDefinitions()
    {
        return $this->embedsMany('App\Models\Devices\PivotDeviceDefinition');
    }
}
