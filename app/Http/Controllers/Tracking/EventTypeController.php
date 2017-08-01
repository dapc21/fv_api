<?php

namespace App\Http\Controllers\Tracking;

use App\Http\Controllers\GenericMongo\DatatrafficController;

class EventTypeController extends DatatrafficController
{
    //Nombre del modelo
    protected $modelo = 'App\Models\Tracking\EventType';
}
