<?php
namespace App\Models\Forms;

use App\datatraffic\lib\Util;
use App\Http\Controllers\GenericMongo\CompanyScope;
use Jenssegers\Mongodb\Eloquent\Model as Moloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use App\Models\Generic\GenericModelTrait;

class Form extends Moloquent
{
    use SoftDeletes;
    use GenericModelTrait;

    //Equibalente a tables en moloquent
    protected $collection = 'forms';
    //Para softdeleted
    protected $dates = ['deleted_at'];
    //Nombre de la columna que hace parte de llave primaria
    protected $primaryKey = '_id';
    //Nombre de los campos calculados
    protected $appends = [];
    //Campos escondidos
    protected $hidden = [];
    //Protegidos
    protected $guarded = [];
    //Utilizado para las relaciones ManyToMany
    public $bIsUnidirectional = true;
    //Nombre de las columnas que se mostraran en la version simple de select y show
    protected $view =[];
    //mapeo de columnas(no se está usando)
    protected $validation_rules = [
        "name" => "required",
        "description" => "required",
        "id_company" => "required",
        "status"=> "required|in:active,inactive",
    ];
    //mapeo de relaciones
    protected $relationship_map = [
        'sections' =>
        [
            'type' => 'embedsmany',
            'foreign_controller' => 'App\Http\Controllers\Forms\SectionController',
            'pivot_id_parent' => '_id',
            'pivot_id_foreign' => 'id_section'
        ],
        'company'=>[
            'type'  =>'onetoone',
            'foreign_controller' => 'App\Http\Controllers\Companies\CompanyController' ,
            'pivot_id_parent' => '_id',
            'pivot_id_foreign'=> 'id_company',
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

    public function sections()
    {
        return $this->hasMany('App\Models\Forms\Section','id_form','_id');
    }
}
