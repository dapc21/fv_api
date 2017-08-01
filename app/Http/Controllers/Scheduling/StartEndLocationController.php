<?php
namespace App\Http\Controllers\Scheduling;

use App\Http\Controllers\GenericMongo\ControllerTraitCompanyCustomAttribute;
use App\Http\Controllers\GenericMongo\DatatrafficController;

class StartEndLocationController extends DatatrafficController
{
    //Nombre del modelo
    protected $modelo = 'App\Models\Scheduling\StartEndLocation';
}