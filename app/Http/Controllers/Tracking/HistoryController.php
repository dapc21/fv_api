<?php

namespace App\Http\Controllers\Tracking;

use App\Http\Controllers\GenericMongo\ControllerTraitCompanyCustomAttribute;
use App\Http\Controllers\GenericMongo\DatatrafficController;

class HistoryController extends DatatrafficController
{
    use ControllerTraitCompanyCustomAttribute;
    
    //Nombre del modelo
    protected $modelo = 'App\Models\Tracking\History';
    
    //Titulo del reporte
    protected $excelTitle = 'Formularios';
    
    //Columnas del reporte
    protected $excelAttributes = '{"updateTime":1,"address":1,"resourceLogin":{"login":1},"deviceData":{"Probe":{"displayLevelGasTank":1}},"speed":1 ,"totalDistance":1,"events":{"description":1}}';
    
    
}
