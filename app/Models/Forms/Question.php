<?php
namespace App\Models\Forms;

use Jenssegers\Mongodb\Eloquent\Model as Moloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use App\Models\Generic\GenericModelTrait;

class Question extends Moloquent
{
    use SoftDeletes;
    use GenericModelTrait;
    
    //Equivalente a tables en moloquent
    protected $collection = 'questions';
    //Para softdeleted
    protected $dates = ['deleted_at','expire'];
    //Nombre de la columna que hace parte de llave primaria
    protected $primaryKey = '_id';
    //Nombre de los campos calculados
    protected $appends = array();
    //Campos escondidos
    protected $hidden = [];
    //Protegidos
    protected $guarded = [];
    //Nombre de las columnas que se mostraran en la version simple de select y show
    protected $view =[];
    //mapeo de columnas(no se está usando)
    protected $validation_rules = [
        "cid" => "required",
        "order" => "required",
        "xtype" => "required",
        "hashtag" => "required",
        "helptext" => "required",
    ];
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

    //CAMPOS CALCULADOS

    //RELACIONES
    
}
