<?php
namespace App\Http\Controllers\Scheduling;

use App\Http\Controllers\GenericMongo\ControllerTraitCompanyCustomAttribute;
use App\Http\Controllers\GenericMongo\DatatrafficController;

class TemporalTaskController extends DatatrafficController
{
    use ControllerTraitCompanyCustomAttribute;

    //Nombre del modelo
    protected $modelo = 'App\Models\Scheduling\TemporalTask';
}