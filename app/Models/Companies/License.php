<?php
namespace App\Models\Companies;

use Jenssegers\Mongodb\Eloquent\Model as Moloquent;
use App\Models\Generic\GenericModelTrait;

class License extends Moloquent
{
    //use SoftDeletes;//No porque es embebido
    use GenericModelTrait;

    //Equibalente a tables en moloquent
    protected $collection = 'licenses';//No hace falta porque es embebido
    //Para softdeleted
    protected $dates = ['deleted_at'];
    //Nombre de la columna que hace parte de llave primaria
    protected $primaryKey = '_id'; //No hace falta porque es embebido
    //Nombre de los campos calculados
    protected $appends = array();
    //Nombre de las columnas que se mostraran en la version simple de select y show
    protected $view =[];
    //Reglas de validacion
    protected $validation_rules = [];
    //mapeo de relaciones
    protected $relationship_map =
    [
        'application' =>
        [
            'type'  =>'embedsone',
            'foreign_controller' => 'App\Http\Controllers\Applications\ApplicationController',
            'pivot_id_parent' => '_id',
            'pivot_id_foreign'=> 'id_application',
        ]
    ];

    //Asociaciones permitidas
    protected $whiteWith = ["application"];
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
    public function application()
    {
        return $this->embedsOne('App\Models\Applications\Application');
    }
}
