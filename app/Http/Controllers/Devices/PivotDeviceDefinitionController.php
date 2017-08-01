<?php
namespace App\Http\Controllers\Devices;

use App\Http\Controllers\GenericMongo\DatatrafficController;

class PivotDeviceDefinitionController extends DatatrafficController
{
    //Nombre del modelo
    protected $modelo = 'App\Models\Devices\PivotDeviceDefinition';

    //Manejo de Atributos (Ojo este nombre está repetido)
    //use \App\Http\Controllers\Device\ControllerTraitCustomAttribute;
}
