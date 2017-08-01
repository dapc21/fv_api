<?php
namespace App\Http\Controllers\Users;

use App\Http\Controllers\GenericMongo\DatatrafficController;

class RoleController extends DatatrafficController 
{    
    //Nombre del modelo
    protected $modelo = 'App\Models\Users\Role'; 
}
