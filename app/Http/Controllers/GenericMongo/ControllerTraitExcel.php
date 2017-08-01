<?php

namespace App\Http\Controllers\GenericMongo;

use App\datatraffic\lib\Util;
use Illuminate\Http\Request;
use \COM;
use Log;
use App\Console\Commands\ExportExcelGeneric;

Trait ControllerTraitExcel
{

    public function excel(Request $request) {
        //Usuario
        $actualUser = Util::$insUser;
        $parameters = ' --resource="'.$actualUser->_id.'"';
        
        //inicializa para verificar datos
        $to=null;
        $strFilters="{}";
        $strSort="{}";

        //Controlador
        $controlador = get_class($this);
        $parameters .= ' --controller="'.$controlador.'"';

        //Atributos
        $parameters .= ' --attributes="'.addslashes($this->excelAttributes).'"';

        //Titulo
        $parameters .= ' --title="'.addslashes(trans($this->excelTitle)).'"';
        
        //Filtros
        if($request->has('filters')) {
        	$strFilters=$request->get('filters');
            $filters = addslashes($strFilters);
            $parameters .= ' --filters="'.$filters.'"';
        }

        //Ordenamiento
        if($request->has('sort')) {
        	$strSort= $request->get('sort');
            $sort = addslashes($strSort);
            $parameters .= ' --sort="'.$sort.'"';
        }
        $result=[];
        //veriicar si el comando es para enviar
        $report= new ExportExcelGeneric();
        $query= $report->getQuery($strFilters, get_class($this), $actualUser->_id.'');
        if( $query->count() < 1500){
        	$report->generateReport($to, $actualUser->_id, $controlador, $this->excelAttributes, $this->excelTitle, $strFilters, $strSort,ExportExcelGeneric::REPORT_DOWNLOAD);
        	return;
        }else{
        	
        	$locale = Util::getLocale($request);
        	$parameters .= ' --locale="'.$locale.'"';
        	//Ejecutar comando
        	if(env('OS','LINUX') == 'Windows_NT')
        	{
        		$cmd = 'php.exe '.base_path().'\artisan ExportExcelGeneric '.$parameters;
        		$WshShell = new COM("WScript.Shell");
        		$WshShell->Run($cmd, 0, false);
        	}
        	else
        	{
        		$cmd = 'php '.base_path().'/artisan ExportExcelGeneric '.$parameters;
        		exec($cmd . " > /dev/null &");
        	}
        	$error = false;
        	$msg = trans('general.MSG_OK');
        	$data = [$cmd];
        	$total = 1;
        	$intCode = 200;
        	$view = [];
        	
        	$result = Util::outputJSONFormat($error, $msg, $total, $data, $view);
        }
        return response($result, $intCode);
    }
}
