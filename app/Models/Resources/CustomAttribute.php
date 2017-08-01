<?php
namespace App\Models\Resources;

use Jenssegers\Mongodb\Eloquent\Model as Moloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class CustomAttribute extends Moloquent
{
    use SoftDeletes;
    use \App\Models\Generic\GenericModelTrait;

    //Equibalente a tables en moloquent
    protected $collection = 'customattributes';
    //Para softdeleted
    protected $dates = ['deleted_at'];
    //Nombre de la columna que hace parte de llave primaria
    protected $primaryKey = '_id';
    //Nombre de los campos calculados
    protected $appends = array();
    //Utilizado para las relaciones ManyToMany
    public $bIsUnidirectional = false;    
    //Nombre de las columnas que se mostraran en la version simple de select y show
    protected $view =[];
    //mapeo de columnas(no se está usando)
    protected $validation_rules = [];
    //mapeo de relaciones
    protected $relationship_map = [];

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
}