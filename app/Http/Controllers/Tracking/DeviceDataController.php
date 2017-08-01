<?php

namespace App\Http\Controllers\Tracking;

use App\Http\Controllers\GenericMongo\DatatrafficController;

class DeviceDataController extends DatatrafficController
{
    //Nombre del modelo
    protected $modelo = 'App\Models\Tracking\DeviceData';
}
