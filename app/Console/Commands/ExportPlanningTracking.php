<?php

namespace App\Console\Commands;

use App\datatraffic\lib\Util;
use App\Http\Controllers\Tracking\ActualController;
use App\Models\Forms\Form;
use App\Models\Resources\ResourceInstance;
use App\Models\Tracking\Actual;
use App\Models\Resources\ResourceGroup;
use App\Models\Resources\ResourceDefinition;
use App\Models\Companies\Company;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
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
class ExportPlanningTracking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ExportPlanningTracking {--email= : The email to send}  
                                               {--resource= : Id company} 
                                               {--filters= : filters to apply }';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Export to Excel the planing tracking";

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    	
        Util::$manageAllCompanies = true;
        Util::$manageAllResource = true;

        $to        = $this->option('email');
        $idResource = $this->option('resource');
        $strFilters = $this->option('filters');

        if( is_null($idResource)){
            Log::error("No se puede generar Reporte");
            $this->error("Argumentos no validos");
            return 1;
        }

        $resource= ResourceInstance::find($idResource);
        if( is_null( $resource)){
            Log::error("No se puede generar Reporte");
            $this->error("Recurso no valido");
            return 1;
        }

        $company = $resource->company()->first();
        if( is_null( $company)){
            Log::error("No se puede generar Reporte");
            $this->error("Empresa no valida");
            return 1;
        }

        //se verifica a quien se le envia el correo
        if(!is_null($to)){
            $to=array($to,$resource->email);
        }else{
            $to=array($resource->email);
        }
        $idCompany =$company->_id;

        $pathLogo= public_path()."/logo.jpg";
        $pathToFileOut = storage_path('app/excel/');
        $reflect = new ReflectionClass($this);
        $nameFileOut   = $reflect->getShortName().'_'.Util::generateRandomString();

        //Instanciar controlador, modelo y query
        $controller = new ActualController();
        $parentModel = new Actual();
        $query = $parentModel->newQueryWithoutScopes();

        //hace el tratamiento de los filtros
        $filters = json_decode($strFilters,true);
        if(is_null( $filters ) ||  empty( $filters )){
            $filters = array();
        }
        $filtersData= array();
        $this->getFilters($filters, $filtersData);
        $FiltersToString = $this->toStringFilters( $filtersData );

        //Recuperar la fecha de las tareas a consultar y las condiciones de filtrado para tareas
        $taskConditions = [];
        $date = Carbon::today('GMT-0')->toDateTimeString();
        foreach($filters as $key => $filter)
        {
            //Nivel AND o OR
            foreach($filter as $subKey => $subFilter)
            {
                if($subFilter['field'] === 'route.date')
                {
                    $date = $subFilter['value'];
                    unset($filters[$key][$subKey]);
                }
                else if($subFilter['field'] === 'route.tasks.status')
                {
                    $taskConditions['$and'][] = ['$in' => ['$$task.status', $subFilter['value']]];
                    unset($filters[$key][$subKey]);
                }
                else if($subFilter['field'] === 'route.tasks.code')
                {
                    $taskConditions['$and'][] = ['$eq' => ['$$task.code', $subFilter['value']]];
                    unset($filters[$key][$subKey]);
                }
            }
        }
        $dateRoute = new UTCDatetime(Carbon::createFromFormat('Y-m-d H:i:s',$date,'GMT-0')->getTimestamp() * 1000);

        //Filtramos
        if (!empty($filters)) {
            $query = $controller->filters( $parentModel, $query, $filters );
        }

        //Traer la relacin de ruta
        $query = $query->with(['route' => function($queryRelation) use ($dateRoute, $taskConditions){
            $queryRelation->where('date', '=', $dateRoute);

            if(!empty($taskConditions)) {
                $eloquentBuilder = $queryRelation->getQuery();
                $queryBuilder = $eloquentBuilder->getQuery();
                $queryBuilder->aggregate = ['function' => '', 'columns' => []];
                $queryBuilder->projections =
                    [
                        'tasks' =>
                            [
                                '$filter' => [
                                    'input' => '$tasks',
                                    'as' => 'task',
                                    'cond' =>  $taskConditions
                                ]
                            ],
                        'resourceInstance._id' => 1,
                        'rawShape' => 1,
                        '_id' => 1,
                    ];
                $eloquentBuilder->setQuery($queryBuilder);
                $queryRelation->setQuery($eloquentBuilder);
            }
        }]);

        //se verifican los permisos para el usuario
        $manageallcompanies= Util::checkApplicationAccess($resource, 'API', 'manageallcompanies', "GET");
        if($manageallcompanies == false ){
            $query = $query->where('id_company', '=', ['$ref' => 'companies', '$id' => new ObjectID($idCompany)] ,'and');
        }

        //Crear excel
        $document= $this->createExcel();
        $document->removeSheetByIndex(0);
        $sheetDetailTask = $this->createSheetName($document, "DETALLE TAREA"); 
        $sheettTotalTask = $this->createSheetName($document, "TOTALES TAREA");
        $titlesDetailTask=array(
        		array('title'=>'RECURSO','style'=>$this->getStyleHeaders()),
        		array('title'=>'TIPO TAREA','style'=>$this->getStyleHeaders()),
        		array('title'=>'NOMBRE TAREA','style'=>$this->getStyleHeaders()),
        		array('title'=>'CODIGO TAREA','style'=>$this->getStyleHeaders()),
        		array('title'=>'ESTADO TAREA','style'=>$this->getStyleHeaders()),
        		array('title'=>'FECHA INICIO TAREA','style'=>$this->getStyleHeaders()),
        		array('title'=>'FECHA FIN TAREA','style'=>$this->getStyleHeaders()),
        		array('title'=>'DIRECCION TAREA','style'=>$this->getStyleHeaders()),
        		array('title'=>'LATITUD TAREA','style'=>$this->getStyleHeaders()),
        		array('title'=>'LONGITUD TAREA','style'=>$this->getStyleHeaders()),
        		array('title'=>'FORMULARIOS TAREA','style'=>$this->getStyleHeaders()),
        		array('title'=>'FECHA CHECKIN','style'=>$this->getStyleHeaders()),
        		array('title'=>'LATITUD CHECKIN','style'=>$this->getStyleHeaders()),
        		array('title'=>'LONGITUD CHECKING','style'=>$this->getStyleHeaders()),
        		array('title'=>'FECHA CHECKOUT','style'=>$this->getStyleHeaders()),
        		array('title'=>'LATITUD CHECKOUT','style'=>$this->getStyleHeaders()),
        		array('title'=>'LONGITUD CHECKOUT','style'=>$this->getStyleHeaders())
        );
        $this->drawTitles($sheetDetailTask, $titlesDetailTask, 0, 6);
        
        
        $titlesDetailTask=array(
        		array('title'=>'RECURSO','style'=>$this->getStyleHeaders()),
        		array('title'=>'FECHA ULTIMO REPORTE','style'=>$this->getStyleHeaders()),
        		array('title'=>'DIRECCION','style'=>$this->getStyleHeaders()),
        		array('title'=>'LATITUD','style'=>$this->getStyleHeaders()),
        		array('title'=>'LONGITUD','style'=>$this->getStyleHeaders()),
        		array('title'=>'ESTADO','style'=>$this->getStyleHeaders()),
        		array('title'=>'00:00','style'=>$this->getStyleHeaders()),
        		array('title'=>'01:00','style'=>$this->getStyleHeaders()),
        		array('title'=>'02:00','style'=>$this->getStyleHeaders()),
        		array('title'=>'03:00','style'=>$this->getStyleHeaders()),
        		array('title'=>'04:00','style'=>$this->getStyleHeaders()),
        		array('title'=>'05:00','style'=>$this->getStyleHeaders()),
        		array('title'=>'06:00','style'=>$this->getStyleHeaders()),
        		array('title'=>'07:00','style'=>$this->getStyleHeaders()),
        		array('title'=>'08:00','style'=>$this->getStyleHeaders()),
        		array('title'=>'09:00','style'=>$this->getStyleHeaders()),
        		array('title'=>'10:00','style'=>$this->getStyleHeaders()),
        		array('title'=>'11:00','style'=>$this->getStyleHeaders()),
        		array('title'=>'12:00','style'=>$this->getStyleHeaders()),
        		array('title'=>'13:00','style'=>$this->getStyleHeaders()),
        		array('title'=>'14:00','style'=>$this->getStyleHeaders()),
        		array('title'=>'15:00','style'=>$this->getStyleHeaders()),
        		array('title'=>'16:00','style'=>$this->getStyleHeaders()),
        		array('title'=>'17:00','style'=>$this->getStyleHeaders()),
        		array('title'=>'18:00','style'=>$this->getStyleHeaders()),
        		array('title'=>'19:00','style'=>$this->getStyleHeaders()),
        		array('title'=>'20:00','style'=>$this->getStyleHeaders()),
        		array('title'=>'21:00','style'=>$this->getStyleHeaders()),
        		array('title'=>'22:00','style'=>$this->getStyleHeaders()),
        		array('title'=>'23:00','style'=>$this->getStyleHeaders()),
        		array('title'=>'TOTAL','style'=>$this->getStyleHeaders())
        );
        $now= Carbon::now();
        $this->drawTitles($sheettTotalTask, $titlesDetailTask, 0, 6);
        
        //dibujar los encabezados
        //array("areamerge"=>"A1:C1", "text"=>"Not found","style"=>[], "image"=>"/path/image.jpg")
        $dataLogoDetailsTask = array(
        		               		array("areamerge"=>"A1:C4", "areastyle"=>"A1:C4","style"=>$this->getStyleHeaders(), "image"=>$pathLogo) 
        					   );
        $dataTitleDetailTask = array(
        							array("areamerge"=>"D1:N1", "text"=>"ESTADISTICA DE TAREAS ACTUALES" ),
        							array("areamerge"=>"D2:N4", "text"=> "".$FiltersToString ),
	       							array("areastyle"=>"D1:N4", "style"=> $this->getStyleHeadersFilters() )
        					   );
        $dataFechaDetailTask = array(
						        	array("areamerge"=>"O1:Q4", "areastyle"=>"O1:Q4","text"=>$now->format('Y/m/d H:i:s'),"style"=>$this->getStyleHeaders())
						        );
        
        $this->drawHeader($sheetDetailTask, $dataLogoDetailsTask , $dataTitleDetailTask, $dataFechaDetailTask);
        $sheetDetailTask->getStyle('D2:N4')
        				->getAlignment()
        				->setWrapText(true);
        
        //DIBUJA LOS HEADERS PARA LOS TOTALES
        $dataLogoTotalTask = array(
        		array("areamerge"=>"A1:C4", "areastyle"=>"A1:C4","style"=>$this->getStyleHeaders(), "image"=>$pathLogo)
        );
        $dataTitleTotalTask = array(
        		array("areamerge"=>"D1:AA1", "text" => "ESTADISTICA DE TOTAL TAREAS ACTUALES"),
        		array("areamerge"=>"D2:AA4", "text" => $FiltersToString),		
        		array("areastyle"=>"D1:AA4", "style"=> $this->getStyleHeaders())
        );
        $dataFechaTotalTask = array(
        		array("areamerge"=>"AB1:AE4", "areastyle"=>"AB1:AE4","text"=>$now->format('Y/m/d H:i:s'),"style"=>$this->getStyleHeaders())
        );
        
        $this->drawHeader($sheettTotalTask, $dataLogoTotalTask, $dataTitleTotalTask, $dataFechaTotalTask );
        $sheettTotalTask->getStyle('D2:AA4')
        ->getAlignment()
        ->setWrapText(true);
        
        ////////////////Termina configuracion de encabezados
        //Escribir los registros
        //DB::connection()->enableQueryLog();
       
        $limit = 100;
        $totalRegistros = $query->count();
        $totalPaginas = (int)($totalRegistros/$limit) + 1;
        $page = 1;
		
        $filabaseInit=7;
        $filabase=$filabaseInit;
        $filabasetotalesInit=7;
        $filabasetotales=$filabasetotalesInit;
        $styleLine=0;
        for ($page = 1; $page <= $totalPaginas; $page++)
        {
            //Ejecutamos la consulta configurada
            $results = $controller->paginate($parentModel, $query, $page, $limit, ['*'])->toArray();
            
            $data =$results['data'];
            $totales=array();
            for ( $i=0; $i< count($data); $i++){
            	$row=$data[$i];
            	$tasks= $row['route']['tasks'];
            	//se crean los totales por status para cada recurso
            	$totales=$this->getDefaultStatus();
            	//dd($totales);
            	
            	if( ! empty($tasks) ){
	            	for ( $j=0; $j< count($tasks); $j++){
	            		$task=$tasks[$j];
	            		$vstatus      = $task['status'];
	            		$vmd5status   = md5($vstatus);
	            		$varrivalTime = Carbon::parse( $task['arrival_time'] );
	            		$vhour = $varrivalTime->hour;
	            		if(! key_exists($vmd5status, $totales )){
	            			$totales[$vmd5status]= array();
	            			$totales[$vmd5status]['name']=$vstatus;
	            			$totales[$vmd5status]['hours']=$this->arrayGenerate();
	            		}
	            		$totales[$vmd5status]['hours'][$vhour]= $totales[$vmd5status]['hours'][$vhour] + 1;
	            		 
	            		
	            		$sheetDetailTask->setCellValue("A".$filabase, $row['resourceInstance']['login']);
		            	$sheetDetailTask->setCellValue("B".$filabase, $task['type']);
		            	$sheetDetailTask->setCellValue("C".$filabase, $task['name']);
		            	$sheetDetailTask->setCellValue("D".$filabase, $task['code']);
		            	$sheetDetailTask->setCellValue("E".$filabase, $task['status']);
		            	$sheetDetailTask->setCellValue("F".$filabase, $task['arrival_time']);
		            	$sheetDetailTask->setCellValue("G".$filabase, $task['finish_time']);
		            	$sheetDetailTask->setCellValue("H".$filabase, $task['address']);
		            	$sheetDetailTask->setCellValue("I".$filabase, $task['location']['lat']);
		            	$sheetDetailTask->setCellValue("J".$filabase, $task['location']['lng']);
		            	//optiene los nombres de los formularios
		            	$stforms="";
		            	if( is_array($task['forms']) ){
		            		for ( $k=0; $k< count($task['forms']); $k++){
		            			$vitemform=$task['forms'][$k];
		            			$vform=$this->getForm($vitemform['id_form']);
		            			if( !empty($vform)){
		            				$vform= $vform->toArray();
		            				$stforms .= $vform['name'];
		            				if($k+1 < count($task['forms'])){
		            					$stforms .=" \n";
		            				}
		            			}
		            		}
		            	}
		            	
		            	$sheetDetailTask->setCellValue("k".$filabase, $stforms);
		            	$sheettTotalTask->getStyle("k".$filabase)
		            	->getAlignment()
		            	->setWrapText(true);
		            	//checkin
		            	if(key_exists('checkin', $task)){
		            		$sheetDetailTask->setCellValue("L".$filabase,$task['checkin']['date']);
		            		$sheetDetailTask->setCellValue("M".$filabase,$task['checkin']['location']['lat']);
		            		$sheetDetailTask->setCellValue("N".$filabase,$task['checkin']['location']['lng']);
		            	}else{
		            		$sheetDetailTask->setCellValue("L".$filabase,"");
		            		$sheetDetailTask->setCellValue("M".$filabase,"");
		            		$sheetDetailTask->setCellValue("N".$filabase,"");
		            	}
		            	if(key_exists('checkout', $task)){
		            		$sheetDetailTask->setCellValue("O".$filabase,$task['checkout']['date']);
		            		$sheetDetailTask->setCellValue("P".$filabase,$task['checkout']['location']['lat']);
		            		$sheetDetailTask->setCellValue("Q".$filabase,$task['checkout']['location']['lng']);	            	
		            	}else{
		            		$sheetDetailTask->setCellValue("O".$filabase,"");
		            		$sheetDetailTask->setCellValue("P".$filabase,"");
		            		$sheetDetailTask->setCellValue("Q".$filabase,"");
		            	}
		            	
		            	if($filabase%2 == 0){
		            		$sheetDetailTask->getStyle('A'.$filabase.':Q'.$filabase)->applyFromArray($this->getStyleTableData());
		            	}
		            	$filabase++;
	            	}
            	}else{
            		$sheetDetailTask->setCellValue("A".$filabase, $row['resourceInstance']['login']);
            		if($filabase%2 == 0){
            			$sheetDetailTask->getStyle('A'.$filabase.':Q'.$filabase)->applyFromArray($this->getStyleTableData());
            		}
            		$filabase++;
            	}
            	
            	
            	//escribir la segunda hoja
            	if(! empty( $totales )){
            		$colIndex= PHPExcel_Cell::columnIndexFromString("F");
            		
	            	foreach ($totales as $vtotalc){
	            		//dd("pepa", $colIndex,$vtotalc);
	            		$horasTotales=$vtotalc['hours'];
	            		$nameType =$vtotalc['name'];
	            		
	            		
	            		$sheettTotalTask->setCellValue("A".$filabasetotales, $row['resourceInstance']['login']);
	            		$sheettTotalTask->setCellValue("B".$filabasetotales, $row['updateTime']);
	            		$sheettTotalTask->setCellValue("C".$filabasetotales, $row['address']);
	            		$sheettTotalTask->setCellValue("D".$filabasetotales, $row['latitude']);
	            		$sheettTotalTask->setCellValue("E".$filabasetotales, $row['longitude']);
	            		$sheettTotalTask->setCellValue("F".$filabasetotales, $vtotalc['name']);
	            		$vtotal=0;
	            		$l=0;
	            		for ( ; $l< count($horasTotales); $l++){
	            			$vtotal += $horasTotales[$l];
	            			$sheettTotalTask->setCellValueByColumnAndRow( ($colIndex + $l) , $filabasetotales , $horasTotales[$l] );
	            		}
	            		//totaliza
	            		$sheettTotalTask->setCellValueByColumnAndRow( ($colIndex + $l) , $filabasetotales , $vtotal );
	            		if($filabasetotales%2 == 0){
	            			$sheettTotalTask->getStyle('F'.$filabasetotales.':AE'.$filabasetotales)->applyFromArray($this->getStyleTableData());
	            		}
	            		$filabasetotales++;
	            	}
            	}else{
            		$sheettTotalTask->setCellValue("A".$filabasetotales, $row['resourceInstance']['login']);
            		$sheettTotalTask->setCellValue("B".$filabasetotales, $row['updateTime']);
            		$sheettTotalTask->setCellValue("C".$filabasetotales, $row['address']);
            		$sheettTotalTask->setCellValue("D".$filabasetotales, $row['latitude']);
            		$sheettTotalTask->setCellValue("E".$filabasetotales, $row['longitude']);
            		if($filabasetotales%2 == 0){
            			$sheettTotalTask->getStyle('F'.$filabasetotales.':AE'.$filabasetotales)->applyFromArray($this->getStyleTableData());
            		}
            		$filabasetotales++;
            	} 
            }
            
            $sheetDetailTask->getStyle('A'.$filabaseInit.':Q'.($filabase - 1))->applyFromArray($this->getStyleTable());
            for($m=$filabaseInit; $m< $filabase; $m++){
            	if($m%2==0){
	            	$sheetDetailTask->getStyle('A'.$m.':Q'.$m)
	            					->getFill()
	            					->applyFromArray(array(
								        'type' => PHPExcel_Style_Fill::FILL_SOLID,
								        'startcolor' => array(
								             'rgb' => 'd9d9d9'
								      )));
            	}
            	$sheetDetailTask->getStyle('A'.$m.':Q'.$m)
            					->getAlignment()
            					->setWrapText(true);
            }
            $sheettTotalTask->getStyle('A'.$filabasetotalesInit.':AE'.($filabasetotales - 1))->applyFromArray($this->getStyleTable());
            for($m=$filabasetotalesInit; $m< $filabasetotales; $m++){
            	if($m%2==0){
            		$sheettTotalTask->getStyle('F'.$m.':AE'.$m)
            		->getFill()
            		->applyFromArray(array(
            				'type' => PHPExcel_Style_Fill::FILL_SOLID,
            				'startcolor' => array(
            						'rgb' => 'd9d9d9'
            				)));
            	}
            	$sheettTotalTask->getStyle('A'.$m.':AE'.$m)
            	->getAlignment()
            	->setWrapText(true);
            }
            
            foreach(range('A','Z') as $columnID) {
            	$sheetDetailTask->getColumnDimension($columnID)
            	->setAutoSize(true);
            }
            foreach(range('A','Z') as $columnID) {
            	$sheettTotalTask->getColumnDimension($columnID)
            	->setAutoSize(true);
            }
            foreach(range('A','E') as $columnID) {
            	$sheettTotalTask->getColumnDimension("A".$columnID)
            	->setAutoSize(true);
            }           
            $this->saveExcel($document, $pathToFileOut,$nameFileOut);
            //dump(DB::connection()->getQueryLog());
        }

        $subject = '[FIELDVISION] Estadistica de tareas actuales';
        $messageView = 'email.exportRegisters';
        //$this->sendEmail($to, $subject, $messageView, $pathToFileOut.$nameFileOut);

        return 0;
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
		    		$sheet->mergeCells( $customStyle['areamerge']);
		    	}
		    	
		    	if(key_exists('text', $customStyle)){
		    		$sheet->setCellValue($vcell, $customStyle['text']);
		    	}
		    	
		    	if(key_exists('image', $customStyle)){
		    		try {
		    			if(file_exists ($customStyle['image']) ){
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
    								$filters[1]['value'][] =$company->name;
    							}
    						}
    					}else{
    						if(is_string($value['value'])){
    							$company= Company::find( $value['value'] );
    							$filters[1]['value'][] =$company->name;
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
    								$filters[3]['value'][] =$resourceGroup->name;
    							}
    						}
    					}else{
    						if(is_string($value['value'])){
    							$resourceGroup= ResourceDefinition::find( $value['value'] );
    							$filters[3]['value'][] =$resourceGroup->name;
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
    								$filters[4]['value'][] =$resourceDef->name;
    							}
    						}
    					}else{
    						if(is_string($value['value'])){
    							$resourceDef= ResourceDefinition::find( $value['value'] );
    							$filters[4]['value'][] =$resourceDef->name;
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
