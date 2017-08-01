<?php
namespace App\Http\Controllers\Forms;

use App\Http\Controllers\GenericMongo\DatatrafficController;

class QuestionController extends DatatrafficController
{
    //Nombre del modelo
    protected $modelo = 'App\Models\Forms\Question';
}
