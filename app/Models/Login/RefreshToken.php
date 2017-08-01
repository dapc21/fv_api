<?php
/**
 * Created by PhpStorm.
 * User: Desarrollo
 * Date: 22/05/2016
 * Time: 09:56 PM
 */

namespace App\Models\Login;

use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Jenssegers\Mongodb\Eloquent\Model as Moloquent;

class RefreshToken extends Moloquent
{
    use SoftDeletes;
    use \App\Models\Generic\GenericModelTrait;

    //Equibalente a tables en moloquent
    protected $collection = 'refreshTokens';
    //Para softdeleted
    protected $dates = [];
    //Nombre de la columna que hace parte de llave primaria
    protected $primaryKey = '_id';
    //Nombre de los campos calculados
    protected $appends = array();
    //Nombre de las columnas que se mostraran en la version simple de select y show
    protected $view =[];
    //mapeo de columnas(no se está usando)
    protected $validation_rules = [];
    //mapeo de relaciones
    protected $relationship_map = [
        'resourceInstance'=>[
            'type'  =>'onetoone',
            'foreign_controller' => 'App\Http\Controllers\Resources\ResourceInstanceController' ,
            'pivot_id_parent' => '_id',
            'pivot_id_foreign'=> 'id_resourceInstance',
        ],
        'deviceInstance'=>[
            'type'  =>'onetoone',
            'foreign_controller' => 'App\Http\Controllers\Devices\DeviceInstanceController' ,
            'pivot_id_parent' => '_id',
            'pivot_id_foreign'=> 'id_deviceInstance',
        ]
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
    public function resourceInstance()
    {
        return $this->belongsTo('App\Models\Resources\ResourceInstance', 'id_resourceInstance', '_id');
    }

    public function deviceInstance()
    {
        return $this->belongsTo('App\Models\Device\DeviceInstance', 'id_deviceInstance', '_id');
    }
}
