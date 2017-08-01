<?php
namespace App\Models\Resources;

use Jenssegers\Mongodb\Eloquent\Model as Moloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class RelatedResourceDefinition extends Moloquent
{
    use SoftDeletes;
    use \App\Models\Generic\GenericModelTrait;

    //Equibalente a tables en moloquent
    protected $collection = 'relatedresourcedefinitions';
    //Para softdeleted
    protected $dates = ['deleted_at'];
    //Nombre de la columna que hace parte de llave primaria
    protected $primaryKey = '_id';
    //Nombre de los campos calculados
    protected $appends = array();
    //Nombre de las columnas que se mostraran en la version simple de select y show
    protected $view =[];
    //mapeo de columnas(no se está usando)
    protected $validation_rules = [];
    //mapeo de relaciones
    protected $relationship_map = [];/*
        'resourceInstance'=>[
            'type'  =>'onetoone',
            'foreign_controller' => 'App\Http\Controllers\Resources\ResourceInstanceController' ,
            'pivot_id_parent' => '_id',
            'pivot_id_foreign'=> 'id_resourceInstance',
        ]
    ];*/

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
/*
    //RELACIONES
    public function resourceInstance()
    {
        return $this->belongsTo('App\Models\Resources\ResourceInstance', 'id_resourceInstance', '_id');
    }*/
}
