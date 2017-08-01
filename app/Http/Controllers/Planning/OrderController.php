<?php
namespace App\Http\Controllers\Planning;

use App\Http\Controllers\GenericMongo\ControllerTraitCompanyCustomAttribute;
use App\Http\Controllers\GenericMongo\DatatrafficController;

class OrderController extends DatatrafficController
{
    use ControllerTraitCompanyCustomAttribute;

    //Nombre del modelo
    public $modelo = 'App\Models\Planning\Order';
}
