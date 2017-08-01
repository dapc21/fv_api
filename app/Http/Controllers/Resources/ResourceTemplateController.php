<?php
namespace App\Http\Controllers\Resources;

use App\Http\Controllers\GenericMongo\DatatrafficController;

class ResourceTemplateController extends DatatrafficController 
{
    //Nombre del modelo
    protected $modelo = 'App\Models\Resources\ResourceTemplate';
}
