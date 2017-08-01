<?php

namespace App\Console\Commands;

use App\datatraffic\lib\Util;
use App\Http\Controllers\Forms\RegisterController;
use App\Models\Forms\Form;
use App\Models\Forms\Register;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDatetime;
use Log;
use App\Models\Resources\ResourceInstance;
use App\datatraffic\lib\UtilExcel;
use \PHPExcel;
use \PHPExcel_Cell;
use App\Models\Planning\Task;
use MongoDB\Exception\RuntimeException;
use phpDocumentor\Transformer\Writer\Exception\RequirementMissing;
use App\Models\Resources\ResourceGroup;
use App\Models\Companies\Company;
use App\Models\Resources\ResourceDefinition;

class ExportExcelFormRegisters extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ExportExcelFormRegisters  
                                                  {--email= : The email to send}
    											  {--resource= : Id del recurso quien genera el reporte} 
    											  {--idFormType= : Identificador del formulario a exportar}
    											  {--filters= : filters to apply }
                                                  {--sort= : sorts to apply }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Export to CSV form's registers";
    
    //mapeo de campos para los indices
    protected  $mappingName=[];
    
    protected $totalReg=0;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    	ini_set('memory_limit', '-1');
    	$cacheMethod = \PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
    	$cacheSettings = array( 'memoryCacheSize' => '1024MB');
    	\PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
    	
        Util::$manageAllCompanies = true;
        Util::$manageAllResource = true;
        //DB::connection()->enableQueryLog();
        $resource=null;
        $idcompany=null;
        $formType=null;
        $filters = [];
        $sorts=[];
        $to =[];
        $strfilters="";
        $company=null;
        
        $title="Listado de Formularios registrados";
        //extrae email 
        if ($this->hasOption( 'email' ) && !is_null( $this->option( 'email' ) ) ) {
        	$to[]=$this->option( 'email' );
        }
        
        //obtiener el id del recurso y el recurso
        if ($this->hasOption( 'resource' )) {
        	$idresource     = $this->option( 'resource' );
        	$resourceObject = new ResourceInstance();
        	$resourceQuery  = $resourceObject->newQueryWithoutScopes();
        	$resource = $resourceQuery->where('_id',new ObjectID($idresource))->first();        	
        	if( is_null( $resource )){
        		throw new RuntimeException(trans("error.resource_not_found"));
        		return  1;
        	}
        	$company= $resource->company()->first();
        	$idcompany= $company->_id;
        	$to[]=$resource->email;
        }else{
        	throw new RequirementMissing(trans("error.resource_requiered"));
        	return 2;
        }
        
        //obtiene el tipo de formulario
        if ($this->hasOption( 'idFormType' )) {
        	$id_formType=$this->option( 'idFormType' );
        	$formTypeObject = new Form();
        	$formTypeQuery = $formTypeObject->newQueryWithoutScopes();
        	$formType = $formTypeQuery->where('_id',new ObjectID($id_formType))->first();
        	if( is_null( $formType ) ){
        		throw new RuntimeException(trans("error.form_not_found"));
        		return  3;
        	}
        }else{
        	throw new RequirementMissing(trans("error.form_requiered"));
        	return 4;
        }
        
        //obtiene los filtros a aplicar
        if($this->hasOption('filters' ) ){
        	$strFilters= $this->option('filters');
        	$filters = json_decode($strFilters,true);
        }
        //obtiene el ordenamiento
        if($this->hasOption('sort' ) ){
        	$strsort= $this->option('sort');
        	$sorts = json_decode( $strsort, true );
        }
        
        //elige el nombre del directrio a salir
        $pathToFileOut = storage_path('app/excel/');        
        $nameFileOut   = 'Register_'. Util::cleanString( $formType->name ) .'_'. Util::generateRandomString() .'.xlsx';

        //Recuperar tipo de formulario y sus columnas
        $headers = [];
        $headers['id'] = 'ID';
        $headers['login'] = 'Recurso';
        $headers['code'] = 'Codigo';
        $headers['name'] = 'Nombre';
        $headers['address'] = 'Direccion';
        $headers['arrival_time'] = 'Fecha inicio programada';
        $headers['finish_time'] = 'Fecha fin programada';
        $headers['status'] = 'Estado';
        
        $sectionsHeaders=[];
        $columnas = [];
        $titles=[ 'name'=>1, 'login'=>1, 'arrival_time'=>1, 'finish_time'=>1 ];
        $this->mappingName=['name'=>1, 'login'=>2, 'arrival_time'=>3, 'finish_time'=>4];
        $index=5;
        if($formType)
        {
            $sections = $formType->sections;
            foreach ($sections as $section) {
                $questions = $section['questions'];
                $sectionsHeader=['name'=>$section->name];
                $vsections=[];
				$vquestiontable=[];
				foreach ($questions as $question) {
					//if( strcmp($question->xtype, 'gridfield')== 0 ){
					$vquestiontable[$question->cid]=$question->toArray();
					$vquestiontable[$question->cid]['parents']=[];
				}
				//se embeben las secciones a los parent y se desasocian del inicial
                foreach ($vquestiontable  as $key=> $question) {
                	if( array_key_exists('id_parent',$question )){
                		$vquestiontable[$question['id_parent']]['parents'][$key]=$question;
                		unset($vquestiontable[ $key ]);
                	}
                }
               	$titlessection=[];
               	foreach ($vquestiontable  as $key=> $question) {
               		if( empty($question['parents']) ){
               			$titlessection[$question['configuration']['fieldLabel']]=1;
               			$this->mappingName[$question['cid']]=$index++;
               		}else{
               			foreach ($question['parents'] as $key=>$value){
               				$titlessection[$question['configuration']['fieldLabel']][$value['configuration']['fieldLabel']]=1;
               				$this->mappingName[$value['cid']]=$index++;
               			}
               		}
               	}
               	$titles[$section->name]=$titlessection;
            }
        }
        
        if ( !empty($filters) ) {
        	$arrayfilters=[];
        	$this->getFilters($filters, $arrayfilters );//"falta";
        	//dd($arrayfilters);
        	foreach ($arrayfilters as $value) {
        		$strfilters .= " ".$value['name'].": ";
        		foreach ($value['value'] as $valores){
        			$strfilters .= $valores. " ";
        		}
        		$strfilters .="\n";
        	}
        }
        
        //crea el documento
        $document= UtilExcel::createDocExcel();
        $document->removeSheetByIndex(0);        
        $nameSheet= trans( "registers" );
        $nameSheet=UtilExcel::generateNameSheet( $nameSheet );
        $sheet = UtilExcel::createSheetName($document, $nameSheet);
        //escribe los header del excel
        UtilExcel::writeHearders($sheet, $title ,$strfilters,count( $this->mappingName ),"");
        
        //donde empiezan los titulos
        $profundidad= UtilExcel::countProfundidad($titles);
        $rowTitleStart    = 7;
        $columnTitleStart = 1;
        //donde empieza la data
        $rowdataStart= $rowTitleStart + $profundidad;
        $columnDataStart= $columnTitleStart;
        //donde va la escritura de la data
        $rowdataEnd= $rowdataStart;
        $columnDataEnd= $columnDataStart;
        
        $columLetterStar= PHPExcel_Cell::stringFromColumnIndex( $columnDataStart );
        $columLetterEnd= PHPExcel_Cell::stringFromColumnIndex( $columnDataStart + count($this->mappingName) -1);
        UtilExcel::writeTitles($sheet, $titles, $rowTitleStart, $columnTitleStart,UtilExcel::countAnchura($titles), UtilExcel::countProfundidad($titles)-1);
                
        $controller = new RegisterController();
        $parentModel = new Register();
        $query = $parentModel->newQueryWithoutScopes();
        
        //Filtramos
        if (!empty($filters)) {
        	$query = $controller->filters( $parentModel, $query, $filters );
        }
        
        //se verifican los permisos para el usuario
        $manageallcompanies= Util::checkApplicationAccess($resource, 'API', 'manageallcompanies', "GET");
        if($manageallcompanies == false ){
        	$query->where('id_company', ['$ref' => 'companies', '$id' => new ObjectID( $idcompany )]);
        }
        
        //Ordenamos
        if (!empty($sorts)){
        	$query = $controller->orders($parentModel, $query, $sorts);
        }

        //Escribir los registros
        //DB::connection()->enableQueryLog();
        $limit = 100;
        $totalRegistros = $query->count();
        $totalPaginas = (int)($totalRegistros/$limit) + 1;
        $page = 1;
        $totalReg=0;
        for ($page = 1; $page <= $totalPaginas; $page++)
        {
            //Ejecutamos la consulta configurada
            $results = $controller->paginate($parentModel, $query, $page, $limit, ['*']);            
            $rownext =  $this->writeResultExcel($sheet, $columnDataStart, $rowdataEnd, $results,$columLetterStar,$columLetterEnd);
         	$rowdataEnd = $rownext;
            //Log::info($results);
            gc_collect_cycles();            
            //$this->writeCSVFile($pathToFile, $columnas, $results);
        }
        UtilExcel::saveExcel($document, $pathToFileOut,$nameFileOut);
        //Log::info(DB::connection()->getQueryLog());
        $subject = '[FIELDVISION] Descarga de registros'; 
        $messageView = 'email.exportRegisters';
        $this->sendEmail($to, $subject, $messageView, $pathToFileOut.'/'.$nameFileOut);
    }

    private function writeCSVFileHeader($pathToFile, $headers)
    {
        $file = fopen($pathToFile, 'a');
        fputcsv($file, $headers);
        fclose($file);
    }
    
    
    private function writeResultExcel(&$sheet, $column, $row,$results,$columLetterStar,$columLetterEnd){
    	$resultcol=1;
    	
    	foreach ($results as $result)
    	{	
    		$maxreg=0;
    		$profundidadMax=1;
    		$dataweb=$result->dataWeb;
    		$id_task= $result->id_task;
    		$sheet->setCellValueByColumnAndRow(2,$row,$result->login);
    		if(!is_null($id_task)){
    			$id_task=$id_task['$id'];
    			$task= Task::where(['_id'=>$id_task])->first();
    			if($task){
	    			$sheet->setCellValueByColumnAndRow(1,$row,$task->name);
	    			$sheet->setCellValueByColumnAndRow(3,$row,$task->arrival_time);
	    			$sheet->setCellValueByColumnAndRow(4,$row,$task->finish_time);
    			}
    		}
    		
    		
    		
    		foreach ($dataweb as $key=> $register){
    			if(!is_array($register) ){
    				$indexcol=$this->mappingName[$key];
    				$sheet->setCellValueByColumnAndRow($indexcol,$row,$register);
    			}else{
    				foreach ($register as $index => $subregister){ //numerico
    					foreach ($subregister as $cid=> $valuereg){//clave reg
    						$indexcol=$this->mappingName[$cid];
    						$sheet->setCellValueByColumnAndRow($indexcol,$row+$index,$valuereg);
    						if( $index > $maxreg){
    							$maxreg= $index;
    						}
    					}
    				}
    			}
    		}
    		//aplicar el estilo a los datos escritos
    		if($this->totalReg % 2 == 1){
    			$sheet->getStyle( $columLetterStar.$row.':'.$columLetterEnd.($row + $maxreg) )
    			->applyFromArray(
    					UtilExcel::getStyleBordersAndFillColor(true,true,true,true,true,"d9d9d9")
    					);
    		}else{
    			$sheet->getStyle( $columLetterStar.$row.':'.$columLetterEnd.($row + $maxreg) )
    			->applyFromArray(
    					UtilExcel::getStyleBordersAndFillColor(true,true,true,true,true,"FFFFFF")
    					);
    		}
    		$this->totalReg++;
    		$row = $row + 1 + $maxreg;
    	}
    	return $row;
    	
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
    
    public function getFilters($json, &$filters){
    	foreach ($json as $value){
    		if(is_array($value) && key_exists('field', $value)){
    			$field= $value['field'];
    			$name= trans('general.'.$field);
    			switch ($field) {
    				case "login":
    					$filters[1]['field']=$field;
    					$filters[1]['name'] =$name;
    					$filters[1]['value'][] =$value['value'];
    					break;
    				case "arrival_time":
    					$filters[2]['field']=$field;
    					$filters[2]['name'] =$name;
    					$filters[2]['value'][] =$value['value'];
    					break;
    				case "finish_time":
    					$filters[3]['field']=$field;
    					$filters[3]['name'] =$name;
    					$filters[3]['value'][] =$value['value'];
    					break;
    				case "status":
    					$filters[4]['field']=$field;
    					$filters[4]['name'] =$name;
    					$filters[4]['value'][] =$value['value'];
    					break;
    				case "task.type":
    					$filters[5]['field']=$field;
    					$filters[5]['name'] =$name;
    					$filters[5]['value'][] =$value['value'];
    					break;
    				case "id_formType":
    					if(is_string( $value['value'] )){
    						$filters[6]['field']=$field;
    						$filters[6]['name'] =$name;
    						$formTypeObject = new Form();
    						$formTypeQuery = $formTypeObject->newQueryWithoutScopes();
    						$formType = $formTypeQuery->where('_id',new ObjectID( $value['value'] ))->first();
    						if( is_null( $formType ) ){
    							$filters[6]['value'][] =$formType->name;
    						}else{
    							$filters[6]['value'][] ='';
    						}
    					}
    					break;
    				
    				case "id_company":
    					$filters[7]['field']=$field;
    					$filters[7]['name'] =$name;
    					if(is_array($value['value'])){
    						foreach ($value['value'] as $vv){
    							if(is_string($vv)){
    								$company= Company::find( $vv );
    								if(!is_null( $company )){
    									$filters[7]['value'][] =$company->name;
    								}else{
    									$filters[7]['value'][] ='null';
    								}
    							}
    						}
    					}else{
    						if(is_string($value['value'])){
    							$company= Company::find( $value['value'] );
    							if(!is_null( $company )){
    								$filters[7]['value'][] =$company->name;
    							}else{
    								$filters[7]['value'][] ='null';
    							}
    						}
    
    					}
    
    					break;
    				case "resourceInstance.login":
    					$filters[8]['field']=$field;
    					$filters[8]['name'] =$name;
    					$filters[8]['value'][] =$value['value'];
    					break;
    				case "resourceInstance.resourceGroups":
    					$filters[9]['field']=$field;
    					$filters[9]['name'] =$name;
    					if(is_array($value['value'])){
    						foreach ($value['value'] as $vv){
    							if(is_string($vv)){
    								$resourceGroup= ResourceGroup::find( $vv );
    								if(!is_null( $resourceGroup )){
    									$filters[9]['value'][] =$resourceGroup->name;
    								}else{
    									$filters[9]['value'][] ='null';
    								}
    							}
    						}
    					}else{
    						if(is_string($value['value'])){
    							$resourceGroup= ResourceGroup::find( $value['value'] );
    							if(!is_null( $resourceGroup )){
    								$filters[9]['value'][] =$resourceGroup->name;
    							}else{
    								$filters[9]['value'][] ='null';
    							}
    								
    						}
    					}
    					break;
    				case "resourceInstance.id_resourceDefinition":
    					$filters[10]['field']=$field;
    					$filters[10]['name'] =$name;
    					if(is_array($value['value'])){
    						foreach ($value['value'] as $vv){
    							if(is_string($vv)){
    								$resourceDef= ResourceDefinition::find( $vv );
    								if(!is_null( $resourceDef )){
    									$filters[10]['value'][] =$resourceDef->name;
    								}else{
    									$filters[10]['value'][] ='null';
    								}
    							}
    						}
    					}else{
    						if(is_string($value['value'])){
    							$resourceDef= ResourceDefinition::find( $value['value'] );
    							if(!is_null( $resourceDef )){
    								$filters[10]['value'][] =$resourceDef->name;
    							}else{
    								$filters[10]['value'][] ='null';
    							}
    						}
    					}
    
    					break;
    				case "tasks.status":
    					$filters[11]['field']=$field;
    					$filters[11]['name'] =$name;
    					$filters[11]['value'][] =$value['value'];
    					break;
    			}
    		}elseif (is_array($value)){
    			$this->getFilters($value,$filters);
    		}
    	}
    }

}
