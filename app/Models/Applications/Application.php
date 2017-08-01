<?php
namespace App\Models\Applications;

use Jenssegers\Mongodb\Eloquent\Model as Moloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use App\Models\Generic\GenericModelTrait;

class Application extends Moloquent
{
    use SoftDeletes;
    use GenericModelTrait;

    //Equibalente a tables en moloquent
    protected $collection = 'applications';
    //Para softdeleted
    protected $dates = ['deleted_at'];
    //Nombre de la columna que hace parte de llave primaria
    protected $primaryKey = '_id';
    //Nombre de los campos calculados
    protected $appends = array();
    //Nombre de las columnas que se mostraran en la version simple de select y show
    protected $view = [];
    //Reglas de validacion
    protected $validation_rules = [
        "name" => "required",
        "modules.*.name" => "required"
    ];
    //mapeo de relaciones
    protected $relationship_map = [];

    //Asociaciones permitidas
    protected $whiteWith = [];
    protected $colums_identify = [];
    public  $colums_title =[];
    //define el orden de las columnas a exportar ['key'=>[''=>'valuetitle']]
    //key es el nomnbre de la columna a exportar
    //valuetitle es el titulo que va a llevar la columna 
    public $columnsToExport=[
    	     'name'=>['title'=>'excel.export.application_Name'],    		 
    ];
    //define las relaciones y el orden en que se van a exportar 
    public $relateToExport=[
    		
    ];
    protected $excel_with_tables =[];
    protected $excel_change_data =[];

    //No se USA
    public function scopeCustomWhere($query)
    {
    	return $query;
    }

    //RELACIONES
}
