<?php
namespace App\Http\Controllers\Devices;

use App\Http\Controllers\GenericMongo\DatatrafficController;

class ConfigurationController extends DatatrafficController 
{    
    //Nombre del modelo
    protected $modelo = 'App\Models\Devices\DConfiguration';
}
