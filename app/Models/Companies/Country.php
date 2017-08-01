<?php
namespace App\Models\Companies;

use Jenssegers\Mongodb\Eloquent\Model as Moloquent;
use App\Models\Generic\GenericModelTrait;

class Country extends Moloquent
{
    use GenericModelTrait;

    //Equibalente a tables en moloquent
    protected $collection = 'countries';
    //Para softdeleted
    protected $dates = ['deleted_at'];
    //Nombre de la columna que hace parte de llave primaria
    protected $primaryKey = '_id';
    //Nombre de los campos calculados
    protected $appends = array();
    //Nombre de las columnas que se mostraran en la version simple de select y show
    protected $view = [];
    //Reglas de validacion
    protected $validation_rules = [];
    //mapeo de relaciones
    protected $relationship_map = [];

    //Asociaciones permitidas
    protected $whiteWith = [];

    protected $colums_identify = [];
    protected $colums_title =[];
    protected $excel_with_tables =[];
    protected $excel_change_data =[];

    /**
     *
     * @param unknown $query
     */
    public function scopeCustomWhere($query)
    {
        return $query;
    }

    //RELACIONES
}