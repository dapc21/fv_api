<?php

/**
 * Describir la clase según PHPDOC
 */

namespace App\Http\Controllers\GenericMongo;

use DB;
use Illuminate\Database\QueryException;
use PDOException;
use Exception;
use App\datatraffic\lib\Util;
use Carbon\Carbon;
use Input;

trait ControllerTraitRestore
{
    /**
     * Restaura un usuario eliminado
     */
    public function restore($strId)
    {
        $error = true;
        $msg = trans('general.GENERAL_ERROR');
        $total = 0;
        $data = [];
        $view = null;

        //Recuperamos el usuario eliminado
        $strModelName = $this->modelo;
        $insModel = new $this->modelo();
        $strPrimaryKeyName = $insModel->getPrimaryKey();
        $strModelName::where($strPrimaryKeyName, $strId)->onlyTrashed()->unset('deleted_at');

        //Obtenemos el usuario recuperado
        $insModel = $this->dGetResourceInstanceFromId($strId);

        //Preparamos la respuesta
        if(!empty($insModel)){
            $error = false;
            $msg = trans('general.MSG_OK');
            $data = ["reference" => $strId];
            $total = 1;
        }else{
            $msg = trans('general.MISSING_DATA_PARAMETER');
        }

        //retornamos
        $result = Util::outputJSONFormat($error, $msg, $total, $data, $view);
        return $result;
    }
}