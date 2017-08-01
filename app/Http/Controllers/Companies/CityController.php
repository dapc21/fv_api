<?php
namespace App\Http\Controllers\Companies;

use App\Http\Controllers\GenericMongo\DatatrafficController;

class CityController extends DatatrafficController
{
    //Nombre del modelo
    protected $modelo = 'App\Models\Companies\City';
}