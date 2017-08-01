<?php
namespace App\Http\Controllers\Scheduling;

use App\Http\Controllers\GenericMongo\DatatrafficController;

class PlanningConfigurationController extends DatatrafficController
{
    //Nombre del modelo
    protected $modelo = 'App\Models\Scheduling\PlanningConfiguration';
}
