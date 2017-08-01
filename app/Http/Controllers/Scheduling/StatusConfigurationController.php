<?php
namespace App\Http\Controllers\Scheduling;

use App\Http\Controllers\GenericMongo\DatatrafficController;

class StatusConfigurationController extends DatatrafficController
{
    //Nombre del modelo
    protected $modelo = 'App\Models\Scheduling\StatusConfiguration';
}
