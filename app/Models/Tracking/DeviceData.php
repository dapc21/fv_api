<?php
namespace App\Models\Tracking;

use Jenssegers\Mongodb\Eloquent\Model as Moloquent;
use App\Models\Generic\GenericModelTrait;

class DeviceData extends Moloquent
{
    use GenericModelTrait;
    
    //Equibalente a tables en moloquent
    protected $collection = 'deviceData';
    //Para softdeleted
    protected $dates = ['deleted_at'];
    //Nombre de la columna que hace parte de llave primaria
    protected $primaryKey = '_id';
    //Nombre de los campos calculados
    protected $appends = array();
    //Nombre de las columnas que se mostraran en la version simple de select y show
    protected $view = [];
    //mapeo de columnas(no se está usando)
    protected $columns_map = [];
    //mapeo de relaciones
    protected $relationship_map = [
        'events' =>
        [
            'type' =>'embedsone',
            'foreign_controller' => 'App\Http\Controllers\Tracking\Event',
            'pivot_id_parent' => '_id',
            'pivot_id_foreign'=> 'id_event',
        ],
    ];
    
    //Asociaciones permitidas
    protected $whiteWith = ['events'];
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
    public function events()
    {
        return $this->embedsMany('App\Models\Tracking\Event');
    }
}
