<?php
namespace App\Models\Companies;

use Jenssegers\Mongodb\Eloquent\Model as Moloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use App\Models\Generic\GenericModelTrait;

class Company extends Moloquent
{
    use SoftDeletes;
    use GenericModelTrait;

    //Equibalente a tables en moloquent
    protected $collection = 'companies';
    //Para softdeleted
    protected $dates = ['deleted_at'];
    //Nombre de la columna que hace parte de llave primaria
    protected $primaryKey = '_id';
    //Nombre de los campos calculados
    protected $appends = array();
    //Nombre de las columnas que se mostraran en la version simple de select y show
    protected $view = [];
    //Reglas de validacion
    protected $validation_rules = [
        "name" => "required|unique:companies,name,NULL,_id,deleted_at,NULL",
        "status"=> "required|in:active,inactive",
        "nit" => "required|unique:companies,nit,NULL,_id,deleted_at,NULL",
        "address" => "required",
        "id_country" => "required|string",
        "id_city" => "required|string",
        "phone" => "required",
        "legalRepresentativeName" => "required|string",
        "legalRepresentativeLastName" => "required|string",
        "legalRepresentativeId" => "required",
        "legalRepresentativePhone" => "required",
    ];
    //mapeo de relaciones
    protected $relationship_map =
        [
            'country'=>
            [
                'type'  =>'onetoone',
                'foreign_controller' => 'App\Http\Controllers\Companies\CountryController' ,
                'pivot_id_parent' => '_id',
                'pivot_id_foreign'=> 'id_country',
            ],
            'city'=>
            [
                'type'  =>'onetoone',
                'foreign_controller' => 'App\Http\Controllers\Companies\CityController' ,
                'pivot_id_parent' => '_id',
                'pivot_id_foreign'=> 'id_city',
            ],
            'licenses' =>
            [
                'type' => 'embedsmany',
                'foreign_controller' => 'App\Http\Controllers\Companies\LicenseController',
                'pivot_id_parent' => '_id',
                'pivot_id_foreign' => 'id_license'
            ],
            'file' =>
            [
                'type' =>'embedsone',
                'foreign_controller' => 'App\Http\Controllers\Scheduling\FileController',
                'pivot_id_parent' => '_id',
                'pivot_id_foreign'=> 'id_file',
            ],
            'planningConfiguration' =>
            [
                'type' =>'embedsone',
                'foreign_controller' => 'App\Http\Controllers\Scheduling\PlanningConfigurationController',
                'pivot_id_parent' => '_id',
                'pivot_id_foreign'=> 'id_file',
            ],
            'statusConfigurations' =>
            [
                'type'  =>'embedsmany',
                'foreign_controller' => 'App\Http\Controllers\Scheduling\StatusConfigurationController',
                'pivot_id_parent' => '_id',
                'pivot_id_foreign'=> 'id_statusConfiguration',
            ],
            'startEndLocations' =>
            [
                'type'  =>'embedsmany',
                'foreign_controller' => 'App\Http\Controllers\Scheduling\StartEndLocationController',
                'pivot_id_parent' => '_id',
                'pivot_id_foreign'=> 'id_startEndLocation',
            ],
            'workingDayConfiguration' =>
            [
                'type' =>'embedsone',
                'foreign_controller' => 'App\Http\Controllers\Scheduling\WorkingDayConfigurationController',
                'pivot_id_parent' => '_id',
                'pivot_id_foreign'=> 'id_workingDayConfiguration',
            ],
        ];

    //Asociaciones permitidas
    protected $whiteWith = ["licenses","country","city"];

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

    //Reglas de validacion
    public function getValidationRules($method, $id_company)
    {
        $validation_rules = $this->validation_rules;

        switch($method)
        {
            case 'UPDATE':
                $validation_rules["name"] = "required|unique:companies,name,".$this->_id.",_id,deleted_at,NULL";
                $validation_rules["nit"] = "required|unique:companies,nit,".$this->_id.",_id,deleted_at,NULL";
                break;
        }
        return $validation_rules;
    }

    //RELACIONES
    public function licenses()
    {
        return $this->embedsMany('App\Models\Companies\License');
    }

    public function country()
    {
        return $this->belongsTo('App\Models\Companies\Country', 'id_country', '_id');
    }

    public function city()
    {
        return $this->belongsTo('App\Models\Companies\City', 'id_city', '_id');
    }

    public function file()
    {
        return $this->embedsOne('App\Models\Scheduling\File');
    }

    public function planningConfiguration()
    {
        return $this->embedsOne('App\Models\Scheduling\PlanningConfiguration');
    }

    public function statusConfigurations()
    {
        return $this->embedsMany('App\Models\Scheduling\StatusConfiguration');
    }

    public function startEndLocations()
    {
        return $this->embedsMany('App\Models\Scheduling\StartEndLocation');
    }

    public function workingDayConfiguration()
    {
        return $this->embedsOne('App\Models\Scheduling\WorkingDayConfiguration');
    }

    //Otros metodos
    public function getLocation(){
        //Encontrar coordenadas del mapa
        $location = ['lat' => 4.710989, 'lng' => -74.072092, 'proj' => 'EPSG:4326'];
        $city = $this->city;
        if($city)
        {
            $location = ['lat' => $city->latitude, 'lng' => $city->longitude, 'proj' => 'EPSG:4326'];
        }

        return $location;
    }
}
