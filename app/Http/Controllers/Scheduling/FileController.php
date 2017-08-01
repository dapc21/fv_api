<?php
namespace App\Http\Controllers\Scheduling;

use App\Http\Controllers\GenericMongo\ControllerTraitCompanyCustomAttribute;
use App\Http\Controllers\GenericMongo\DatatrafficController;

class FileController extends DatatrafficController
{
    use ControllerTraitCompanyCustomAttribute;

    //Nombre del modelo
    protected $modelo = 'App\Models\Scheduling\File';
}
