<?php
namespace App\Http\Controllers\TestMongo;

use Carbon\Carbon;
use App\datatraffic\lib\Util;
use App\datatraffic\lib\Configuration;
use App\datatraffic\lib\ErrorMessages;
use App\datatraffic\dao\Generic;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Auth;
use App\Http\Controllers\GenericMongo\DatatrafficController;


class DispositivoController extends DatatrafficController {
    
    // Nombre del modelo
    protected $modelo = 'App\Models\TestMongo\Dispositivo';
    
    public function getModelo() {
        return $this->modelo;
    }
    
}
