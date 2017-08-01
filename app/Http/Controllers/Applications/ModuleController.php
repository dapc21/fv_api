<?php
namespace App\Http\Controllers\Applications;

use App\Http\Controllers\GenericMongo\DatatrafficController;

class ModuleController extends DatatrafficController 
{    
    //Nombre del modelo
    protected $modelo = 'App\Models\Applications\Module';
}
