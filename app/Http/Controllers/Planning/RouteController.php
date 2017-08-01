<?php
namespace App\Http\Controllers\Planning;

use App\Http\Controllers\GenericMongo\ControllerTraitCompanyCustomAttribute;
use App\Http\Controllers\GenericMongo\DatatrafficController;

class RouteController extends DatatrafficController
{
    use ControllerTraitCompanyCustomAttribute;

    //Nombre del modelo
    protected $modelo = 'App\Models\Planning\Route';
}