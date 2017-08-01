<?php
namespace App\Models\Forms;

use App\datatraffic\lib\Util;
use App\Http\Controllers\GenericMongo\CompanyScope;
use App\Http\Controllers\GenericMongo\ResourceScope;
use Jenssegers\Mongodb\Eloquent\Model as Moloquent;
use App\Models\Generic\GenericModelTrait;

class Register extends Moloquent
{
    use GenericModelTrait;
    
    //Coleccion
    protected $collection = 'registers';
    //Para softdeleted
    protected $dates = ['arrival_time'];
    //Nombre de la columna que hace parte de llave primaria
    protected $primaryKey = '_id';
    //Nombre de los campos calculados
    protected $appends = [];
    //Nombre de las columnas que se mostraran en la version simple de select y show
    protected $view = [];
    //mapeo de columnas(no se está usando)
    protected $validation_rules = [];
    //mapeo de relaciones
    protected $relationship_map = [
        'company'=>[
            'type'  =>'onetoone',
            'foreign_controller' => 'App\Http\Controllers\Companies\CompanyController' ,
            'pivot_id_parent' => '_id',
            'pivot_id_foreign'=> 'id_company',
        ],
        'task' =>
        [
            'type' =>'onetoone',
            'foreign_controller' => 'App\Http\Controllers\Planning\TaskController',
            'pivot_id_parent' => '_id',
            'pivot_id_foreign'=> 'id_task',
        ],
        'form' =>
        [
            'type' =>'onetoone',
            'foreign_controller' => 'App\Http\Controllers\Forms\FormController',
            'pivot_id_parent' => '_id',
            'pivot_id_foreign'=> 'id_form',
        ],
        'resourceInstance' =>
        [
            'type' =>'onetoone',
            'foreign_controller' => 'App\Http\Controllers\Resources\ResourceInstanceController',
            'pivot_id_parent' => '_id',
            'pivot_id_foreign'=> 'id_resourceInstance',
        ],
    ];
    
    //Asociaciones permitidas
    protected $whiteWith = ['company','task','form','resourceInstance'];
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
   
    //RELACIONES
    public function company()
    {
        return $this->belongsTo('App\Models\Companies\Company', 'id_company', '_id');
    }

    public function task()
    {
        return $this->belongsTo('App\Models\Planning\Task', 'id_task', '_id')->withTrashed();
    }

    public function form()
    {
        return $this->belongsTo('App\Models\Forms\Form', 'id_form', '_id')->withTrashed();
    }

    public function resourceInstance()
    {
        return $this->belongsTo('App\Models\Resources\ResourceInstance', 'id_resourceInstance', '_id');
    }
}
