<?php

namespace App\Http\Controllers\Controls;

use App\Http\Controllers\GenericMongo\DatatrafficController;
use App\Http\Controllers\GenericMongo\ControllerTraitCompanyCustomAttribute;

class GeofenceController extends DatatrafficController
{
    use ControllerTraitCompanyCustomAttribute;
    
    //Nombre del modelo
    protected $modelo = 'App\Models\Controls\Geofence';
}
