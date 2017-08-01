<?php
namespace App\Models\Forms;

use Jenssegers\Mongodb\Eloquent\Model as Moloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use App\Models\Generic\GenericModelTrait;

class Section extends Moloquent
{
    use SoftDeletes;
    use GenericModelTrait;
    
    //Equibalente a tables en moloquent
    protected $collection = 'sections';
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
    //Nombre de las columnas que se mostraran en la version simple de select y show
    protected $view =[];
    //mapeo de columnas(no se está usando)
    protected $validation_rules = [
        "name" => "required",
        "id_form" => "required",
        "status"=> "required|in:active,inactive",
    ];
    //mapeo de relaciones
    protected $relationship_map = [
        /*'form' =>
        [
            'type' => 'onetoone',
            'foreign_controller' => 'App\Http\Controllers\Forms\FormController',
            'pivot_id_parent' => '_id',
            'pivot_id_foreign' => 'id_form'
        ],*/
        'questions' =>
        [
            'type' => 'embedsmany',
            'foreign_controller' => 'App\Http\Controllers\Forms\QuestionController',
            'pivot_id_parent' => '_id',
            'pivot_id_foreign' => 'id_question'
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

    //CAMPOS CALCULADOS

    //RELACIONES
    /*public function form()
    {
        return $this->belongsTo('App\Models\Forms\Form', 'id_form', '_id');
    }*/

    public function questions()
    {
        return $this->embedsMany('App\Models\Forms\Question');
    }
}
