<?php
namespace App\Models\Planning;

use App\datatraffic\lib\Util;
use App\Http\Controllers\GenericMongo\CompanyScope;
use App\Http\Controllers\GenericMongo\ResourceScope;
use Jenssegers\Mongodb\Eloquent\Model as Moloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use App\Models\Generic\GenericModelTrait;

class Order extends Moloquent
{
    use SoftDeletes;
    use GenericModelTrait;

    //Equibalente a tables en moloquent
    protected $collection = 'orders';
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
        'company'=>
        [
            'type'  =>'onetoone',
            'foreign_controller' => 'App\Http\Controllers\Companies\CompanyController' ,
            'pivot_id_parent' => '_id',
            'pivot_id_foreign'=> 'id_company',
        ],
        'process' =>
        [
            'type' =>'onetoone',
            'foreign_controller' => 'App\Http\Controllers\Scheduling\ProcessController',
            'pivot_id_parent' => '_id',
            'pivot_id_foreign'=> 'id_process',
        ],
        'resourceInstance' =>
        [
            'type' =>'onetoone',
            'foreign_controller' => 'App\Http\Controllers\Resources\ResourceInstanceController',
            'pivot_id_parent' => '_id',
            'pivot_id_foreign'=> 'id_resourceInstance',
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
    protected static function boot()
    {
        parent::boot();

        if(!Util::$manageAllCompanies) {
            static::addGlobalScope(new CompanyScope());
        }

        if(!Util::$manageAllResource) {
            static::addGlobalScope(new ResourceScope());
        }
    }

    //No se USA
    public function scopeCustomWhere($query)
    {
    	return $query;
    }

    //CAMPOS CALCULADOS
   
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
        return $this->belongsTo('App\Models\Resources\ResourceInstance', 'id_resourceInstance', '_id');
    }
}
