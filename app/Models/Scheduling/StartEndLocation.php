<?php
namespace App\Models\Scheduling;

use Jenssegers\Mongodb\Eloquent\Model as Moloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use App\Models\Generic\GenericModelTrait;

class StartEndLocation extends Moloquent
{
    //use SoftDeletes;
    use GenericModelTrait;

    //Equibalente a tables en moloquent
    protected $collection = 'startendlocations';
    //Para softdeleted
    protected $dates = ['deleted_at'];
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
        'resourceDefinition' =>
        [
            'type'  =>'onetoone',
            'foreign_controller' => 'App\Http\Controllers\Resources\ResourceDefinitionController',
            'pivot_id_parent' => '_id',
            'pivot_id_foreign'=> 'id_resourceDefinition',
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

    //No se USA
    public function scopeCustomWhere($query)
    {
    	return $query;
    }

    //CAMPOS CALCULADOS
   
    //RELACIONES
    public function resourceDefinition()
    {
        return $this->belongsTo('App\Models\Scheduling\ResourceDefinition', 'id_resourceDefinition', '_id');
    }
}
