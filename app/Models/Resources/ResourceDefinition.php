<?php
namespace App\Models\Resources;

use App\datatraffic\lib\Util;
use App\Http\Controllers\GenericMongo\CompanyScope;
use Jenssegers\Mongodb\Eloquent\Model as Moloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use \App\Models\Generic\GenericModelTrait;

class ResourceDefinition extends Moloquent
{
    use SoftDeletes;
    use GenericModelTrait;

    //Equibalente a tables en moloquent
    protected $collection = 'resourceDefinitions';
    //Para softdeleted
    protected $dates = ['deleted_at'];
    //Nombre de la columna que hace parte de llave primaria
    protected $primaryKey = '_id'; //No hace falta porque es embebido
    //Nombre de los campos calculados
    protected $appends = array();
    //Campos escondidos
    protected $hidden = [];
    //Protegidos
    protected $guarded = ['company','isSystem'];
    //Utilizado para las relaciones ManyToMany
    public $bIsUnidirectional = true;
    //Nombre de las columnas que se mostraran en la version simple de select y show
    protected $view = [];
    //mapeo de columnas(no se está usando)
    protected $validation_rules = [
        "name" => "required|uniqueInCompany:resourceDefinitions,name,NULL,_id,id_company,NULL,deleted_at,NULL",
    ];
    //mapeo de relaciones
    protected $relationship_map =
    [
        'company'=>[
            'type'  =>'onetoone',
            'foreign_controller' => 'App\Http\Controllers\Companies\CompanyController' ,
            'pivot_id_parent' => '_id',
            'pivot_id_foreign'=> 'id_company',
        ],
        'deviceDefinitions' =>
        [
            'type'  =>'embedsmany',
            'foreign_controller' => 'App\Http\Controllers\Devices\PivotDeviceDefinitionController',
            'pivot_id_parent' => '_id',
            'pivot_id_foreign'=> 'id_deviceDefinition',
        ],
        'icon' =>
        [
            'type'  =>'onetoone',
            'foreign_controller' => 'App\Http\Controllers\Icons\IconController',
            'pivot_id_parent' => '_id',
            'pivot_id_foreign'=> 'id_icon',
        ],
        /*'resourceDefinitions' =>
        [
            'type'  =>'embedsmany',
            'foreign_controller' => 'App\Http\Controllers\Resources\RelatedResourceDefinitionController',
            'pivot_id_parent' => '_id',
            'pivot_id_foreign'=> 'id_resourceDefinition',
        ]*/
    ];

    //Asociaciones permitidas
    protected $whiteWith = ['customAttributes', 'deviceDefinitions', 'resourceDefinitions','icon'];
    protected $colums_identify = [];
    protected $colums_title =[ 'name' ];
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

    //Reglas de validacion
    public function getValidationRules($method, $id_company)
    {
        if(is_null($id_company)){
            $id_company = (string)$this->id_company['$id'];
        }

        $validation_rules = $this->validation_rules;
        switch($method)
        {
            case 'CREATE':
                $validation_rules["name"] = "required|uniqueInCompany:resourceDefinitions,name,NULL,_id,id_company,".$id_company.",deleted_at,NULL";
                break;
            case 'UPDATE':
                $validation_rules["name"] = "required|uniqueInCompany:resourceDefinitions,name,".$this->_id.",_id,id_company,".$id_company.',deleted_at,NULL';
                break;
        }
        return $validation_rules;
    }

    //RELACIONES
    public function company()
    {
        return $this->belongsTo('App\Models\Companies\Company', 'id_company', '_id');
    }

    public function deviceDefinitions()
    {
        return $this->embedsMany('App\Models\Devices\PivotDeviceDefinition');
    }

    public function icon()
    {
        return $this->belongsTo('App\Models\Icons\Icon', 'id_icon', '_id');
    }

    /*public function resourceDefinitions()
    {
        return $this->embedsMany('App\Models\Resources\RelatedResourceDefinition');
    }*/

    //MUTATORS
    /*public function setIsSystemAttribute($value)
    {
        $this->attributes['isSystem'] = false;
    }*/
}
