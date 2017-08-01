<?php

namespace App\Http\Controllers\Messages;

use App\Http\Controllers\GenericMongo\DatatrafficController;

class MessageController extends DatatrafficController
{
    //Nombre del modelo
    protected $modelo = 'App\Models\Messages\Message';
}
