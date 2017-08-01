<?php
namespace App\Http\Controllers\Resources;

use App\Http\Controllers\GenericMongo\DatatrafficController;

class CustomAttributeController extends DatatrafficController
{
    //Nombre del modelo
    protected $modelo = 'App\Models\Resources\CustomAttribute';
}
