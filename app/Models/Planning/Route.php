<?php
namespace App\Models\Planning;

use App\datatraffic\lib\Util;
use App\Http\Controllers\GenericMongo\CompanyScope;
use Jenssegers\Mongodb\Eloquent\Model as Moloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Route extends Moloquent
{
    use SoftDeletes;
    use \App\Models\Generic\GenericModelTrait;

    //Equibalente a tables en moloquent
    protected $collection = 'routes';
    //Para softdeleted
    protected $dates = ['deleted_at','date'];
    //Nombre de la columna que hace parte de llave primaria
    protected $primaryKey = '_id';
    //Nombre de los campos calculados
    protected $appends = array();
    //Campos escondidos
    protected $hidden = [];
    //Protegidos
    protected $guarded = ['company'];
    //Nombre de las columnas que se mostraran en la version simple de select y show
    protected $view = [];
    //mapeo de columnas(no se está usando)
    protected $validation_rules = [];
    //mapeo de relaciones
    protected $relationship_map = [
        'company'=>
            [
                'type'  =>'onetoone',
                'foreign_controller' => 'App\Http\Controllers\Companies\CompanyController' ,
                'pivot_id_parent' => '_id',
                'pivot_id_foreign'=> 'id_company',
            ],
        'process'=>
            [
                'type'  =>'onetoone',
                'foreign_controller' => 'App\Http\Controllers\Scheduling\ProcessController' ,
                'pivot_id_parent' => '_id',
                'pivot_id_foreign'=> 'id_process',
            ],
        'resourceInstance' =>
            [
                'type' =>'embedsone',
                'foreign_controller' => 'App\Http\Controllers\Resources\ResourceInstanceController',
                'pivot_id_parent' => '_id',
                'pivot_id_foreign'=> 'id_resourceInstance',
            ],
        'tasks' =>
            [
                'type' =>'embedsmany',
                'foreign_controller' => 'App\Http\Controllers\Planning\TaskController',
                'pivot_id_parent' => '_id',
                'pivot_id_foreign'=> 'id_task',
            ],
    ];

    //Asociaciones permitidas
    protected $whiteWith = ['company','order','resourceInstance','process','forms'];
    protected $colums_identify = [];
    protected $colums_title =[];
    protected $excel_with_tables =[];
    protected $excel_change_data =[];

    //Boot
    protected static function boot()
    {
        parent::boot();

        if(!Util::$manageAllCompanies) {
            static::addGlobalScope(new CompanyScope());
        }
    }

    //No se USA
    public function scopeCustomWhere($query)
    {
        return $query;
    }

    //RELACIONES
    public function company()
    {
        return $this->belongsTo('App\Models\Companies\Company', 'id_company', '_id');
    }

    public function process()
    {
        return $this->belongsTo('App\Models\Scheduling\Process', 'id_process', '_id');
    }

    public function resourceInstance()
    {
        return $this->embedsOne('App\Models\Resources\ResourceInstance');
    }

    public function tasks()
    {
        return $this->embedsMany('App\Models\Planning\Task');
    }
}