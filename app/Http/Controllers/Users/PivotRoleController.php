<?php
namespace App\Http\Controllers\Users;

use App\Http\Controllers\GenericMongo\DatatrafficController;

class PivotRoleController extends DatatrafficController
{    
    //Nombre del modelo
    protected $modelo = 'App\Models\Users\PivotRole';
}
