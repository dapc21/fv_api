<?php

namespace App\Console\Commands;

use App\datatraffic\lib\Util;
use App\Http\Controllers\Tracking\ActualController;
use App\Models\Forms\Form;
use App\Models\Tracking\Actual;
use App\Models\Resources\ResourceGroup;
use App\Models\Resources\ResourceDefinition;
use App\Models\Companies\Company;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDatetime;
use Log;

use \PHPExcel;
use \PHPExcel_IOFactory;
use \PHPExcel_Worksheet;
use \PHPExcel_Cell;
use \PHPExcel_Worksheet_MemoryDrawing;
use \PHPExcel_Style_Border;
use \PHPExcel_Style_Fill;  
use \PHPExcel_Style_NumberFormat;
use \PHPExcel_Style_Alignment;
use \ReflectionClass;

/**
 * 
 * @author nestorrojas@datatraffic
 *
 */
class ExportActualResourceTracking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ExportActualResourceTracking {email} {idCompany}  {filters?} ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Export to Excel the actual status by type of resource";

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    	
        Util::$manageAllCompanies = true;
        Util::$manageAllResource = true;

        $to = $this->argument('email');
        $idCompany = $this->argument('idCompany');

        $strFilters = $this->argument('filters');
        $pathLogo= public_path()."/logo.jpg";
        $pathToFileOut = storage_path('app/excel/');
        $reflect = new ReflectionClass($this);
        $nameFileOut   = $reflect->getShortName().'_'.Util::generateRandomString();
        //hace el tratamiento de los filtros
        $filters = json_decode($strFilters,true);
        if(is_null( $filters )){
        	$filters = '{}';
        }
        
        $filtersData= array();
        $this->getFilters($filters, $filtersData);
        //convierte los filtros a string legible
        $FiltersToString = $this->toStringFilters( $filtersData );
        
        
        //crea el documento
        $document= $this->createExcel();
        $document->removeSheetByIndex(0);       
        //crea las hojas para el recurso
        $resourcesDef=\App\Models\Resources\ResourceDefinition::where('id_company', '=', ['$ref' => 'companies', '$id' => new ObjectID($idCompany)] ,'and')
        ->get();
        $createSheet=false;
        if( !empty($resourcesDef) ){
        	foreach ($resourcesDef as $resourceDef){
        		$createSheet=true;
        		$nameSheet=$this->generateNameSheet($resourceDef->name);
        		$sheet = $this->createSheetName($document, $nameSheet);
        		$this->exportActualByResource($sheet, $filters, $idCompany, $resourceDef->_id,$resourceDef->name,$FiltersToString);
        	}
        }
        if($createSheet==false){
        	$sheet = $this->createSheetName($document, "data");
        }
        
        $this->saveExcel($document, $pathToFileOut,$nameFileOut);
        $subject = '[FIELDVISION] Estadistica de tareas actuales';
        $messageView = 'email.exportRegisters';
        $this->sendEmail($to, $subject, $messageView, $pathToFileOut.$nameFileOut);
    }
    
    /**
     * Exporta el actual en una hoja
     * @param unknown $sheet
     * @param unknown $filters
     * @param unknown $idcompany
     * @param unknown $id_resourceDefinition
     */
    public function exportActualByResource(&$sheet, $filters, $idCompany, $id_resourceDefinition, $nameResourceDefinition,$FiltersToString="",$pathLogo=null){
    	$deviceDataToPrint=array(
    			'Tablet'=>array("speed"=>array('title'=>'Velocidad'),"heading"=>array('title'=>'Orientacion'),"batteryLevel"=>array('title'=>'Nivel Bateria')  ),
    			'Probe'=>array("displayLevelGasTank"=>array('title'=>'Combustible')  ),
    			'Ecumonitor'=>array("RPM"=>array('title'=>'RPM')),
    			'LoadSensor'=>array("isFrontLoader"=>array('title'=>'Carga Delantera') ,"isBehindLoader"=>array('title'=>'Carga Trasera') ),
    			'PassangerSensor'=>array("passagerExist"=>array('title'=>'Tiene pasajero') ),		
    			'Seal'=>array("sealValue"=>array('title'=>'Valor Precinto')  ,"consecutiveValue"=>array('title'=>'Consecutivo Precinto')   ),		
    			'Trailer'=>array("hasTrailer"=>array('title'=>'Tiene Trailer')  ),		
    	);
    	$titles=array();
    	$columnIndex=8;
    	//dd($FiltersToString);
    	$controller = new ActualController();
    	$parentModel = new Actual();
    	$query = $parentModel->newQueryWithoutScopes();
    	
    	//Filtramos
    	if (!empty($filters)) {
    		$query = $controller->filters( $parentModel, $query, $filters );
    	}
    	$query->where('resourceInstance.id_company', ['$ref' => 'companies', '$id' => new ObjectID($idCompany)])
    		  ->where('resourceInstance.id_resourceDefinition', ['$ref' => 'resourceDefinitions','$id' => new ObjectID( $id_resourceDefinition )]);
    	//Ordenamos
    	if ( !empty($sorts) ){
    		$query = $controller->orders( $parentModel , $query, $sorts);
    	}
    	
    	
    	$limit = 100;
    	$totalRegistros = $query->count();
    	
    	$totalPaginas = (int)($totalRegistros/$limit) + 1;
    	$page = 1;
    	    	
    	$filabaseInit=7;
    	$filabase=$filabaseInit;
    	$filabasetotalesInit=7;
    	$filabasetotales=$filabasetotalesInit;
    	$styleLine=0;
    	$esconder=true;
    	for ($page = 1; $page <= $totalPaginas; $page++)
    	{
    		//Ejecutamos la consulta configurada
    		$results = $controller->paginate($parentModel, $query, $page, $limit, ['*'])->toArray();
    		
    		$data =$results['data'];
    		$totales=array();
    		for ( $i=0; $i< count($data); $i++){
    			$row=$data[$i];
    			$esconder=false;
    			$sheet->setCellValue("A".$filabase, $row['resourceInstance']['login']);
    			$sheet->setCellValue("B".$filabase, $nameResourceDefinition);
    			$sheet->setCellValue("C".$filabase, $row['updateTime']);
    			$sheet->setCellValue("D".$filabase, $row['speed']);
    			$sheet->setCellValue("E".$filabase, $row['address']);
    			$sheet->setCellValue("F".$filabase, $row['latitude']);
    			$sheet->setCellValue("G".$filabase, $row['longitude']);
    			$geofences= $row['actualGeofences'];
    			$strGeofeces="";
    			if( !empty($geofences) && is_array($geofences) ){
    				foreach ($geofences as $geofece) {
    					$strGeofeces .= $geofece['name'].",";
    				}
    			}
    			$sheet->setCellValue("H".$filabase, $strGeofeces);
    			/** $deviceDataToPrint=array(
    			'Ecumonitor'=>array("RPM"=>array()),
    			'LoadSensor'=>array("isFrontLoader"=>array() ,"isBehindLoader"=>array() ),
    			'PassangerSensor'=>array("passagerExist"=>array() ),
    			'Probe'=>array("passagerExist"=>array() ,"isBehindLoader"=>array() ),
    			'PassangerSensor'=>array("computedProbeValue"=>array() ),
    			
    	);*/
    			if(key_exists('deviceData', $row ) && is_array($row['deviceData'])){
    				$devicesdata=$row['deviceData'];
    				foreach ($devicesdata as $keyDevice => $valueDevice) {
    					if(key_exists($keyDevice, $deviceDataToPrint)){
    						foreach ($deviceDataToPrint[$keyDevice] as $property => $valuemetadata) {
    		                    var_dump("property",$property,$valuemetadata);
    							if(!key_exists('index', $deviceDataToPrint[$keyDevice][$property]) ){
    								$deviceDataToPrint[$keyDevice][$property]['index']=$columnIndex;
    								$titles[$columnIndex]=$deviceDataToPrint[$keyDevice][$property]['title'];
    								$columnIndex++;	 
    							}
    							$sheet->setCellValueByColumnAndRow( $deviceDataToPrint[$keyDevice][$property]['index'] , $filabase ,$devicesdata[$keyDevice][$property] );
    							var_dump("clave", $devicesdata[$keyDevice][$property], 'index',$deviceDataToPrint[$keyDevice][$property]['index'] );
    						}				
    					}
    				}
    				//$sheetDetailTask->setCellValue("L".$filabase,$task['checkin']['date']);
    				//$sheetDetailTask->setCellValue("M".$filabase,$task['checkin']['location']['lat']);
    				//$sheetDetailTask->setCellValue("N".$filabase,$task['checkin']['location']['lng']);
    			}
    			
    			
    			$filabase++;
    			
    		}
    		
    	}
    	
    	if($esconder ==true){
    		$sheet->setSheetState(\PHPExcel_Worksheet::SHEETSTATE_VERYHIDDEN);
    	}
    	
    	$titlesSheet=array(
    			array('title'=>'RECURSO','style'=>$this->getStyleHeaders()),
    			array('title'=>'TIPO','style'=>$this->getStyleHeaders()),
    			array('title'=>'FECHA ACTUALIZACION','style'=>$this->getStyleHeaders()),
    			array('title'=>'VELOCIDAD','style'=>$this->getStyleHeaders()),
    			array('title'=>'UBICACION','style'=>$this->getStyleHeaders()),
    			array('title'=>'LATITUD','style'=>$this->getStyleHeaders()),
    			array('title'=>'LONGITUD','style'=>$this->getStyleHeaders()),
    			array('title'=>'GEOCERCAS','style'=>$this->getStyleHeaders()),
    	);
    	foreach ($titles as $key => $value) {
    		$titlesSheet[]= array('title'=>$value,'style'=>$this->getStyleHeaders() );
    	}
    	$now= Carbon::now();
    	$this->drawTitles($sheet, $titlesSheet, 0, 6);
    	$adjustedColumn = PHPExcel_Cell::stringFromColumnIndex( $columnIndex - 1);
    	$sheet->getStyle( 'A'.$filabaseInit.':'.$adjustedColumn.($filabase - 1) )->applyFromArray( $this->getStyleTable() );
    	
    	//impime los encabezados
    	$dataLogoDetailsTask = array(
    			array("areamerge"=>"A1:C4", "areastyle"=>"A1:C4","style"=>$this->getStyleHeaders())  //, "image"=>$pathLogo
    	);
    	$dataTitleDetailTask = array(
    			array("areamerge"=>"D1:J1", "text"=>"DACTOS ACTUALES ".$nameResourceDefinition ),
    			array("areamerge"=>"D2:J4", "text"=> "".$FiltersToString ),
    			array("areastyle"=>"D1:J4", "style"=> $this->getStyleHeadersFilters() )
    	);
    	$dataFechaDetailTask = array(
    			array("areamerge"=>"K1:N4", "areastyle"=>"K1:N4","text"=>$now->format('Y/m/d H:i:s'),"style"=>$this->getStyleHeaders())
    	);
    	
    	$this->drawHeader($sheet, $dataLogoDetailsTask , $dataTitleDetailTask, $dataFechaDetailTask);
    	$sheet->getStyle('D2:N4')
    	->getAlignment()
    	->setWrapText(true);
    	//autodimensiona las columnas
    	for ($i = 0; $i < $columnIndex; $i++) {
    		$columnID= PHPExcel_Cell::stringFromColumnIndex( $i );
    		$sheet->getColumnDimension($columnID)
    			  ->setAutoSize(true);    		
    	}
    	
    	$columnID= PHPExcel_Cell::stringFromColumnIndex( ($columnIndex - 1) );
    	for($m=$filabaseInit; $m< $filabase; $m++){
    		if($m%2==1){
    			$sheet->getStyle('A'.$m.':'.$columnID.$m)
    			->getFill()
    			->applyFromArray(array(
    					'type' => PHPExcel_Style_Fill::FILL_SOLID,
    					'startcolor' => array(
    							'rgb' => 'd9d9d9'
    					)));
    		}
    		$sheet->getStyle('A'.$m.':'.$columnID.$m)
    		->getAlignment()
    		->setWrapText(true);
    	}
    	
    	
    }
    
    public function generateNameSheet( $name="NuevaHoja" ){
    	$name=preg_replace('/[^A-Za-z0-9\-]/', '', $name);
    	return substr($name, 0,30);
    }
    
    
    public function getStyleTable(){
    	return array(
							'font' => array(
									'name' => 'Arial',
									'size' => '8',
							),
							'borders' => array(
									'left' => array(
											'style' => PHPExcel_Style_Border::BORDER_THIN,
									),
									'right' => array(
											'style' => PHPExcel_Style_Border::BORDER_THIN,
									),
									
									'vertical' => array(
											'style' => PHPExcel_Style_Border::BORDER_THIN,
									),
									'bottom' => array(
											'style' => PHPExcel_Style_Border::BORDER_THIN,
									),
							),
							'fill' => array(
									'type' => PHPExcel_Style_Fill::FILL_SOLID,
									'startcolor' => array(
											'argb' => 'FFFFFF',
									),
							),
				  );
    }
    
    public function getStyleTableData(){
    	return array(
    			'font' => array(
    					'name' => 'Arial',
    					'size' => '8',
    			),    			
    			'fill' => array(
    					'type' => PHPExcel_Style_Fill::FILL_SOLID,
    					'startcolor' => array(
    							'argb' => 'FFFFFF',
    					),
    					'color'=>array(
    							'rgb' => 'd9d9d9',
    					),
    			),
    	);
    }
    
    public function getStyleTotals(){
    	return array(
							'font' => array(
									'name' => 'Arial',
									'size' => '9',
									'bold'  => true,
							),
							'borders' => array(
									'left' => array(
											'style' => PHPExcel_Style_Border::BORDER_THIN,
									),
									'right' => array(
											'style' => PHPExcel_Style_Border::BORDER_THIN,
									),
									
									'vertical' => array(
											'style' => PHPExcel_Style_Border::BORDER_THIN,
									),
									'bottom' => array(
											'style' => PHPExcel_Style_Border::BORDER_THIN,
									),
							),
							'fill' => array(
									'type' => PHPExcel_Style_Fill::FILL_SOLID,
									'startcolor' => array(
											'argb' => 'FFFFFF',
									),
							),
						);
    }
    
    public function getStyleHeaders(){
    	return array(
				'font' => array(
						'name' => 'Arial',
						'size' => '10',
						'bold'=>true,
				),
				'borders' => array(
						'left' => array(
								'style' => PHPExcel_Style_Border::BORDER_THIN,
						),
						'right' => array(
								'style' => PHPExcel_Style_Border::BORDER_THIN,
						),
							
						'vertical' => array(
								'style' => PHPExcel_Style_Border::BORDER_THIN,
						),
						'bottom' => array(
								'style' => PHPExcel_Style_Border::BORDER_THIN,
						),
						'top' => array(
								'style' => PHPExcel_Style_Border::BORDER_THIN,
						),
				),
				'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array(
								'argb' => 'FFFFFF',
						),
				),
    			'alignment' => array(
    					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
    			),
		);
    }
    
    public function getStyleHeadersFilters(){
    	return array(
    			'font' => array(
    					'name' => 'Arial',
    					'size' => '8',
    					'bold'=>true,
    			),
    			'borders' => array(
    					'left' => array(
    							'style' => PHPExcel_Style_Border::BORDER_THIN,
    					),
    					'right' => array(
    							'style' => PHPExcel_Style_Border::BORDER_THIN,
    					),
    						
    					'vertical' => array(
    							'style' => PHPExcel_Style_Border::BORDER_THIN,
    					),
    					'bottom' => array(
    							'style' => PHPExcel_Style_Border::BORDER_THIN,
    					),
    					'top' => array(
    							'style' => PHPExcel_Style_Border::BORDER_THIN,
    					),
    			),
    			'fill' => array(
    					'type' => PHPExcel_Style_Fill::FILL_SOLID,
    					'startcolor' => array(
    							'argb' => 'FFFFFF',
    					),
    			),
    			'alignment' => array(
    					'horizontal' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
    			),
    	);
    }
    
    /**
     * 
     * @return multitype:string
     */
    public function getFormatPercentage(){
    	return array( 
            'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00
        );
    }
    
    public function getFormatMoney(){
    	return '"$"#,##0.00_-';
    }
    
    /**
     * 
     * @param unknown $heet sheet
     * @param unknown $data array(["title"=>"my titulo", "style"=>array()],["title"=>"my titulo", "style"=>array()....)
     * @param unknown $colInit
     * @param unknown $rowInit
     */
    public function drawTitles( &$sheet, $data, $colInit, $rowInit ){
    	if(! empty($data)){
    		for($i=0; $i< count($data);$i++){
    			$title= $data[$i];
    			if(key_exists('title', $title)){
    				$sheet->setCellValueByColumnAndRow( ($colInit + $i) , $rowInit , $title['title'] );
    			}
    			
    			if(key_exists('style', $title)){
    				$sheet->getCellByColumnAndRow(($colInit+ $i), $rowInit)
    				      ->getStyle()
    				      ->applyFromArray($title['style']);
    			}
    		}
    	}
    	
    		 
    }
    
    
    public function getForm( $id_form ){
    	$formTypeObject = new Form();
    	$formTypeQuery = $formTypeObject->newQueryWithoutScopes();
    	$formType = $formTypeQuery->where('_id',new ObjectID( $id_form ))->first();
    	return $formType;
    }
    
    /**
     * @param unknown $sheet hoja de trabajo
     * @param unknown $dataLogo  [["areamerge"=>"A1:C1", "areastyle"=>'A1:D10', "text"=>"Not found","style"=>[], "image"=>"/path/image.jpg"] ... ["areamerge"=>"A1:C1", "areastyle"=>'A1:D10', "text"=>"Not found","style"=>[], "image"=>"/path/image.jpg"]]
     * @param unknown $dataTitle [["areamerge"=>"A1:C1", "areastyle"=>'A1:D10', "text"=>"Not found","style"=>[], "image"=>"/path/image.jpg"] ... ["areamerge"=>"A1:C1", "areastyle"=>'A1:D10', "text"=>"Not found","style"=>[], "image"=>"/path/image.jpg"]]
     * @param unknown $dataFecha [["areamerge"=>"A1:C1", "areastyle"=>'A1:D10', "text"=>"Not found","style"=>[], "image"=>"/path/image.jpg"] ... ["areamerge"=>"A1:C1", "areastyle"=>'A1:D10', "text"=>"Not found","style"=>[], "image"=>"/path/image.jpg"]]
     */
    public function drawHeader(&$sheet, $dataLogo,$dataTitle, $dataFecha){
    	
    	$this->applicateStyle($sheet,$dataLogo);
    	$this->applicateStyle($sheet,$dataTitle);
    	$this->applicateStyle($sheet,$dataFecha);
    }
    
    public function applicateStyle(&$sheet, $data=array()){
    	if(is_array($data)){
    		//dd($data);
    		for($i=0; $i< count($data);$i++){
    			$customStyle=$data[$i];
    			$vcell= "A1";
		    	if(key_exists('areamerge', $customStyle)){
		    		$vcell2=explode(':', $customStyle['areamerge']);
		    		$vcell= $vcell2[0];
		    		var_dump( $customStyle['areamerge'] );
		    		$sheet->mergeCells(  $customStyle['areamerge']  );
		    	}
		    	
		    	if(key_exists('text', $customStyle)){
		    		$sheet->setCellValue($vcell, $customStyle['text']);
		    	}
		    	
		    	if(key_exists('image', $customStyle)){
		    		try {
		    			if(!empty($customStyle['image']) && file_exists ($customStyle['image']) ){
		    				$this->insetImageJpg($sheet,$customStyle['image'], 50, 50 ,$vcell);
		    			}
		    			//dd($customStyle['image']);
		    		} catch (Exception $e) {
		    			Log::error("No se pudo cargar imagen ". $customStyle['image']);
		    		}
		    		
		    	}
		    	
		    	if(key_exists('style', $customStyle)){
		    		$areaStyle=$vcell;
		    		if(key_exists('areastyle', $customStyle)){
		    			$areaStyle= $customStyle['areastyle'];
		    		}		    		
		    		$sheet->getStyle( $areaStyle )->applyFromArray( $customStyle['style'] );;
		    	}
		    	
    		}
    	}
    }
    
    public function getFilters($json, &$filters){
    	foreach ($json as $value){
    		if(is_array($value) && key_exists('field', $value)){
    			$field= $value['field'];
    			switch ($field) {
    				case "id_company":
    					$filters[1]['field']=$field;
    					$filters[1]['name'] ="Empresa";
    					if(is_array($value['value'])){
    						foreach ($value['value'] as $vv){
    							if(is_string($vv)){
    								$company= Company::find( $vv );
    								if(!is_null( $company )){
    									$filters[1]['value'][] =$company->name;
    								}else{
    									$filters[1]['value'][] ='null';
    								}
    							}
    						}
    					}else{
    						if(is_string($value['value'])){
    							$company= Company::find( $value['value'] );
    							if(!is_null( $company )){
    								$filters[1]['value'][] =$company->name;
    							}else{
    								$filters[1]['value'][] ='null';
    							}
    						}
    						
    					}
    					    					
    					break;
    				case "resourceInstance.login":
    					$filters[2]['field']=$field;
    					$filters[2]['name'] ="Recurso";
    					$filters[2]['value'][] =$value['value'];    					
    					break;
    				case "resourceInstance.resourceGroups":
    					$filters[3]['field']=$field;
    					$filters[3]['name'] ="Grupo";
    					if(is_array($value['value'])){
    						foreach ($value['value'] as $vv){
    							if(is_string($vv)){
    								$resourceGroup= ResourceGroup::find( $vv );
    								if(!is_null( $resourceGroup )){
    									$filters[3]['value'][] =$resourceGroup->name;
    								}else{
    									$filters[3]['value'][] ='null';
    								}
    							}
    						}
    					}else{
    						if(is_string($value['value'])){
    							$resourceGroup= ResourceDefinition::find( $value['value'] );
    							if(!is_null( $resourceGroup )){
    								$filters[3]['value'][] =$resourceGroup->name;
    							}else{
    								$filters[3]['value'][] ='null';
    							}
    							
    						}
    					}   					
    					break;
    				case "resourceInstance.id_resourceDefinition":
    					$filters[4]['field']=$field;
    					$filters[4]['name'] ="Tipo de Recurso";
    					if(is_array($value['value'])){
    						foreach ($value['value'] as $vv){
    							if(is_string($vv)){
    								$resourceDef= ResourceDefinition::find( $vv );
    								if(!is_null( $resourceDef )){
    									$filters[4]['value'][] =$resourceDef->name;
    								}else{
    									$filters[4]['value'][] ='null';
    								}
    							}
    						}
    					}else{
    						if(is_string($value['value'])){
    							$resourceDef= ResourceDefinition::find( $value['value'] );
    							if(!is_null( $resourceDef )){
    								$filters[4]['value'][] =$resourceDef->name;
    							}else{
    								$filters[4]['value'][] ='null';
    							}
    						}
    					}
    						
    					break;
    				case "tasks.status":
    					$filters[5]['field']=$field;
    					$filters[5]['name'] ="Estado tarea";
    					$filters[5]['value'][] =$value['value'];	
    					break;
    			}
    		}elseif (is_array($value)){
    			$this->getFilters($value,$filters);
    		}
    	}
    }
    
    
    /**
     * 
     * @param unknown $vfilters
     * @return string
     */
    public function toStringFilters($vfilters=array()){
    	
    	$filtros="FILTRADO POR:\n";
    	$i=1;
    	$count= intval(count( $vfilters )/2);
    	foreach ($vfilters as $values){    		
    		if(is_array($values) && key_exists('name', $values) && key_exists('value', $values) && is_array($values['value'])){   			
    			$filtros .= $values['name'].": (";    			
    			foreach ($values['value'] as $value){
    				if(! is_array($value)){
    					$filtros .= $value . ", ";
    				}else{
    					foreach ($value as $v){
    						if(! is_array($v)){
    							$filtros .= $v . ", ";
    						}
    					}
    				}	
    			}    			
    			$filtros .= ") ";	
    		}    		
    		if($i == $count ){
    			$filtros .= "\n";
    		}
    		$i++;
    	}
    	return str_replace(", )",")",$filtros);
    }
    
    
    public function arrayGenerate(){
    	$arr= array();
    	for ( $i=0; $i< 24 ; $i++){
    		$arr[$i]=0;
    	}
    	return $arr;
    }
    
    private function createExcel(){
    	$objXSL = new PHPExcel();
    	
    	return $objXSL;
    }
	
    /**
     * 
     * @param unknown $objXSL
     * @param string $folderDestination
     * @param string $nameFile
     * @return string
     */
    private function saveExcel(&$objXSL, $folderDestination, $nameFile="reporte" ){
    	$objXSL->setActiveSheetIndex(0);    	
    	$objWriter = PHPExcel_IOFactory::createWriter($objXSL, 'Excel2007');
    	//para forzar las graficas
    	$objWriter->setIncludeCharts(true);    	
    	$filesalida=$folderDestination.'/'.$nameFile.'.xlsx';    	
    	$objWriter->save( $filesalida );
    	return $filesalida;
    }
    private function writeCSVFileHeader($pathToFile, $headers)
    {
        $file = fopen($pathToFile, 'a');
        fputcsv($file, $headers);
        fclose($file);
    }
    public function createSheetName( &$objXSL,$nameSheet ){
    	$myWorkSheet = new PHPExcel_Worksheet($objXSL, $nameSheet);
    	// Attach the "My Data" worksheet as the first worksheet in the PHPExcel object
    	$objXSL->addSheet($myWorkSheet); //$objXSL->getSheetCount()
    	
    	return $myWorkSheet;
    }
    public function getDefaultStatus(){
    	$totals= array();
    	$vmds=md5('PENDIENTE');
    	$totals[$vmds]=$this->generateStatusByName(md5('PENDIENTE'), 'PENDIENTE');
    	$vmds=md5('CHECKIN');
    	$totals[$vmds]=$this->generateStatusByName(md5('CHECKIN'), 'CHECKIN');
    	$vmds=md5('CHECKOUT SIN FORMULARIO');
    	$totals[$vmds]=$this->generateStatusByName(md5('CHECKOUT SIN FORMULARIO'), 'CHECKOUT SIN FORMULARIO');
    	$vmds=md5('CHECKOUT CON FORMULARIO');
    	$totals[$vmds]=$this->generateStatusByName(md5('CHECKOUT CON FORMULARIO'), 'CHECKOUT CON FORMULARIO');
    	$vmds=md5('APROBADA');
    	$totals[$vmds]=$this->generateStatusByName(md5('APROBADA'), 'APROBADA');
    	$vmds=md5('CANCELADA');
    	$totals[$vmds]=$this->generateStatusByName(md5('CANCELADA'), 'CANCELADA');
    	
    	return $totals;
    }
    public function generateStatusByName($key,$name){
    	$status= array();
    	$status['name']=$name;
    	$status['hours']=$this->arrayGenerate();
    	return $status;
    }
    
    public function insetImageJpg(&$sheet,$path, $height, $width ,$celda='A1'){
    	$gdImage = imagecreatefromjpeg($path);
    	$objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
    	$objDrawing->setName('Sample image');$objDrawing->setDescription('imagen');
    	$objDrawing->setImageResource($gdImage);
    	$objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_JPEG);
    	$objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
    	$objDrawing->setHeight( $height );
    	$objDrawing->setWidth($width);
    	$objDrawing->setWorksheet( $sheet );
    	$objDrawing->setCoordinates($celda);    	
    }
    

    private function writeCSVFile($pathToFile, $columnas, $results)
    {
        $file = fopen($pathToFile, 'a');
        foreach ($results as $result)
        {
            $row = [];
            array_push($row,(string)$result->_id);
            array_push($row,$result->login);
            $task = $result->task;
            if($task)
            {
                array_push($row,$result->task->code);
                array_push($row,$result->task->name);
                array_push($row,$result->task->address);
                array_push($row,$result->task->arrival_time);
                array_push($row,$result->task->finish_time);
                array_push($row,$result->task->status);
            }
            else{
                array_push($row,"");
                array_push($row,"");
                array_push($row,"");
                array_push($row,"");
                array_push($row,"");
                array_push($row,"");
            }

            //Recuperar columnas
            $dataWeb = $result->dataWeb;
            Log::info($dataWeb);
            foreach ($columnas as $key => $columna)
            {
                if(array_key_exists($key,$dataWeb)){

                    if (is_array($dataWeb[$key])) {
                        $subString = "";
                        foreach ($dataWeb[$key] as $subArray)
                        {
                            $subString .= implode(';', $subArray)."|";
                        }
                        $row[$key] = $subString;
                    } else {
                        if (is_a($dataWeb[$key], 'MongoDB\BSON\UTCDateTime')) {
                            $row[$key] = Carbon::createFromTimestamp($dataWeb[$key]->toDateTime()->getTimestamp(),'GMT-0')->toDateTimeString();
                        }
                        else {
                            $row[$key] = $dataWeb[$key];
                        }
                    }
                } else {
                    $row[$key] = "";
                }
            }
            fputcsv($file, $row);
        }

        fclose($file);
    }

    private function sendEmail($to, $subject, $messageView, $pathToFile){

        $data = [];
        Mail::send($messageView, $data, function ($message) use ($to, $subject, $pathToFile) {
            $message->from(env('MAIL_USERNAME','soporte@datatraffic.com.co'));
            $message->to($to);
            $message->subject($subject);
            $message->attach($pathToFile);
        });
    }

}
