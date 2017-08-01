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
use Illuminate\Http\Request;
use App\Models\Users\User;
use App\Models\Resources\ResourceInstance;
use Illuminate\Support\Facades\Log;
use App\Exceptions\MissingRequestParameter;
use Illuminate\Support\Facades\App;

/**
 * Envia un correo con el comando indicado 
 * php artisan ExportExcelGeneric 
 *     --email="nestorrojas@datatraffic.com.co" 
 *     --resource="5787aae14499e32460034045" 
 *     --controller="App\Http\Controllers\Resources\ResourceInstanceController" 
 *     --attributes="{\"login\":1,\"email\":1,\"company\":{\"name\":1,\"nit\":1},\"roles\":{\"role\":{\"name\":1},\"applicationName\":1}}" 
 *     --title="Reporte Recursos por Empresa" 
 *     --filters="{\"and\":[{\"field\":\"id_resourceDefinition\",\"comparison\":\"eq\",\"value\":\"570f9f46da1c882fea57db56\"},{\"field\":\"customAttributes.nombres\",\"comparison\":\"lk\",\"value\":\"PED\"}]}" 
 *     --sort="{}"
 *  
 * 
 * @author nestorrojas@datatraffic
 *
 */
class ExportExcelGeneric extends Command
{
	
	function __construct(){
		parent::__construct();
		Util::$manageAllCompanies = true;
		Util::$manageAllResource = true;
	}
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ExportExcelGeneric {--email= : The email to send}  
                                               {--resource= : Id company} 
                                               {--controller= : Moldel to query eg. /models/Model} 
                                               {--attributes= : attributes to show}
    										   {--title= : title to report} 
                                               {--filters= : filters to apply }
    		   								   {--locale= : Idioma/Localizacion del reporte }
                                               {--sort= : sorts to apply }';
	
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Export generic report";
    
    protected  $mappingName=[];
    //profundidad por arreglo
    protected $prof=[];
    
    protected  $idx=1;
    protected  $rowActual=0;
    protected  $mappingTextLength=[];//contiene la relacion columna=>lengthCadena
    
    const REPORT_COUNT=1; //cuenta los reportes generados
    const REPORT_SEND=2;  // indica que el reporte se va a enviar
    const REPORT_DOWNLOAD=3; //indica que el reporte se va a descargar
    
    public function handle()
    {
    	Util::$manageAllCompanies = true;
    	Util::$manageAllResource = true;
    
    	$to        = $this->option('email');
    	$idResource = $this->option('resource');
    	$controllerValue     = $this->option('controller');
    	$attributes     = $this->option('attributes');//atributos a mostrar
    	$title     = $this->option('title');//atributos a mostrar
    	$strFilters = $this->option('filters');//filtros del controlador
    	$strSort = $this->option('sort'); 
    	$strLocale= $this->option('locale');
    	App::setLocale($strLocale);
    	$title= trans($title);
    	$result= $this->generateReport($to, $idResource, $controllerValue, $attributes, $title, $strFilters, $strSort,self::REPORT_SEND);    	
    }
    
    /**
     * 
     * @param unknown $strFilters  filtros
     * @param unknown $controllerValue  controlador
     * @param unknown $idResource  identificador del recurso
     * @throws MissingRequestParameter  en caso de no ser valido algun parametro
     * @return Query
     */
    public function getQuery( $strFilters,  $controllerValue, $idResource ){
    	Util::$manageAllCompanies = true;
    	Util::$manageAllResource = true;
    	
    	$resource= ResourceInstance::where('_id',new ObjectID( $idResource ))->first();
    	if( is_null( $resource)){
    		throw new MissingRequestParameter(trans("error.invalid_resource"));
    	}
    	
    	$company = $resource->company()->first();
    	if( is_null( $company)){
    		throw new MissingRequestParameter(trans("error.invalid_company"));
    	}
    	
    	//genera el controlador
    	$controller= new $controllerValue();
    	$vmodel= $controller->getModelo();
    	$model= new $vmodel();
    	
    	//hace el tratamiento de los filtros
    	$filters = json_decode($strFilters,true);
    	if(is_null( $filters ) ||  empty( $filters )){
    		$filters = array();
    	}
    	$query = $model->newQueryWithoutScopes();
    	//Filtramos
    	if (!empty($filters)) {
    		$query = $controller->filters( $model, $query, $filters );
    	}
    	//se verifican los permisos para el usuario
    	$manageallcompanies= Util::checkApplicationAccess($resource, 'API', 'manageallcompanies', "GET");
    	if($manageallcompanies == false ){
    		$query = $query->where('id_company', '=', ['$ref' => 'companies', '$id' => new ObjectID($idCompany)] ,'and');
    	}    	
    	return $query;
    }
    
    
    public function generateReport( $to, $idResource, $controllerValue, $attributes, $title, $strFilters, $strSort, $report_operation=self::REPORT_SEND )
    {
    	Util::$manageAllCompanies = true;
    	Util::$manageAllResource = true;
      
    	if( is_null($idResource) ||  is_null($controllerValue) || is_null($attributes)){
    		throw new MissingRequestParameter(trans("error.invalid_parameter"));
    	}
    
    	$resource= ResourceInstance::where('_id',new ObjectID( $idResource ))->first();
    	if( is_null( $resource)){
    		throw new MissingRequestParameter(trans("error.invalid_resource"));
    	}
    
    	$company = $resource->company()->first();
    	if( is_null( $company)){
    		throw new MissingRequestParameter(trans("error.invalid_company"));
    	}
    	//se verifica a quien se le envia el correo
    	if(!is_null($to)){
    		$to=array($to, $resource->email);
    	}else{
    		$to=array($resource->email);
    	}
    	$idCompany =$company->_id;
    	
    	$filters = json_decode($strFilters,true);
    	if(is_null( $filters ) ||  empty( $filters )){
    		$filters = array();
    	}
    	
    	//arma el query
    	$query= $this->getQuery($strFilters, $controllerValue, $idResource);
    	//aplica el ordenamiento
    	$sorts= json_decode($strSort,true);
    	if (!empty($sorts) ){
    		$query = $controller->orders($model, $query, $sorts);
    	}
    	
    	$controller= new $controllerValue();
    	$vmodel= $controller->getModelo();
    	$model= new $vmodel();
    
    	$pathLogo= public_path()."/logo.jpg";
    	//elige el nombre del directrio a salir
    	$pathToFileOut = storage_path('app/excel/');
    	$reflect = new ReflectionClass( $model );
    	$nameFileOut   = $reflect->getShortName().'_'.Util::generateRandomString().'.xlsx';
    	
    	//arma los encabezados
    	$vattributes=array();
    	$profundidad=1;    	
    	if(!empty($attributes)){
    		$vattributes= json_decode($attributes,true);
    		$profundidad = $this->countProfundidad($vattributes);
    	}
    
    	//extrae los filtros del query
    	$toFiltros=array();
    	$this->extractFilters($filters, $toFiltros); 
    	$convertfilter=array();
    	$convertfilterHojas=array();
    	$strfilters="";
    
    	//se recorre cada filtro y se obtienen las claves y valores de los filtros
    	foreach ($toFiltros as $filt => $vvalue ){
    		$default= array("operation"=>$vvalue["comparison"] , "value"=>$vvalue["value"]);
    		$arrdot=  $this->arrayDot($vvalue["field"],$default);    		 
    		if(!empty($arrdot)){
    			$filterAsString= $model->getFiltersAsString($arrdot,"","",$convertfilterHojas);
    			array_push($convertfilter,$filterAsString);
    		}
    	}
    	//se recorren los filtros obtenidos y se concatena y hace la traduccion de los muismos
    	foreach ($convertfilterHojas as $key=>$value){
    		if( is_array($value) && count($value)>0 && key_exists("filter", $value) && key_exists("path", $value)){
    			$strfilters= $strfilters. trans($value['path'])." : ".$value['filter'] ."  ";
    		}
    	}
    	//se verifican los permisos para el usuario
    	$manageallcompanies= Util::checkApplicationAccess($resource, 'API', 'manageallcompanies', "GET");
    	if($manageallcompanies == false ){
    		$query = $query->where('id_company', '=', ['$ref' => 'companies', '$id' => new ObjectID($idCompany)] ,'and');
    	}
    
    	//crea el documento
    	$document= $this->createExcel();
    	$document->removeSheetByIndex(0);
    
    	$nameSheet= trans( $reflect->getShortName() );
    	$nameSheet=$this->generateNameSheet( $nameSheet );
    	$sheet = $this->createSheetName($document, $nameSheet);
    
    	//donde empiezan los titulos
    	$rowTitleStart    = 7;
    	$columnTitleStart = 1;
    	//donde empieza la data
    	$rowdataStart= $rowTitleStart + $profundidad;
    	$columnDataStart= $columnTitleStart;

    	//donde va la escritura de la data
    	$rowdataEnd= $rowdataStart;
    	$columnDataEnd= $columnDataStart;
    	$this->rowActual = $rowdataEnd;
    
    	//mapea las variables
    	$this->mappingName=$this->generateIndexToPrint( $this->mapArray($vattributes), $columnTitleStart );
    	//calcula donde empieza y termina de escribir
    	$columLetterStar= PHPExcel_Cell::stringFromColumnIndex( $columnDataStart );
    	$columLetterEnd= PHPExcel_Cell::stringFromColumnIndex( $columnDataStart + count($this->mappingName) -1);
    	$this->writeTitles($sheet, $vattributes, $rowTitleStart, $columnTitleStart,$this->countAnchura($vattributes), $this->countProfundidad($vattributes)-1);
    	$createSheet=false;
    	//escribe los header del excel
    	$this->writeHearders($sheet, $title ,$strfilters,count($this->mappingName),"");
    
    	//verifica la cantidad de registros
    	$totalRegistros = $query->count();
    	if( !empty( $totalRegistros > 0) ){
    		$data=array();
    		$totalReg=0;
    		$limit = 100;
    		$totalPaginas = (int)($totalRegistros/$limit) + 1;
    		$page = 1;
    		//pagina los resultados
    		for ($page = 1; $page <= $totalPaginas; $page++)
    		{
    			//Ejecutamos la consulta configurada
    			$results = $controller->paginate($model, $query, $page, $limit, ['*']);
    			foreach ($results as $result){
    				$createSheet=true;
    				//dd($result);
    				$data=$result->toArrayExcel( $vattributes); 
    				$this->prof=[];
    			
    				$rowTotal=  $this->writeArrayToSheet( $sheet, $data, $this->rowActual,"");
    				$rowTotal= $this->getMaxNumber( $this->prof );
    				$this->rowActual = $this->rowActual + $rowTotal;
    				$rowTotalTmp = $rowTotal;
    				if($rowTotal >1){
    					$rowTotalTmp = $rowTotalTmp -1;
    				}
    				//aplicar el estilo a los datos escritos
    				if($totalReg%2 == 1){
    					$sheet->getStyle( $columLetterStar.$rowdataEnd.':'.$columLetterEnd.($rowdataEnd+$rowTotalTmp) )
    					->applyFromArray(
    							$this->getStyleBordersAndFillColor(true,true,true,true,true,"d9d9d9")
    							);
    				}else{
    					$sheet->getStyle( $columLetterStar.$rowdataEnd.':'.$columLetterEnd.($rowdataEnd+$rowTotalTmp) )
    					->applyFromArray(
    							$this->getStyleBordersAndFillColor(true,true,true,true,true,"FFFFFF")
    							);
    				}
    				$rowdataEnd= $rowdataEnd + $rowTotal;
    				$totalReg++;
    			}
    		}
    		 
    		//dump($this->mappingTextLength);
    		//autodimensiona las columnas
    		for ($i = 0; $i < count($this->mappingName)+$columnTitleStart; $i++) {
    			//reajusta la columna en caso de quedar muy grande
    			$columnID= PHPExcel_Cell::stringFromColumnIndex( $i );
    			if( isset($this->mappingTextLength[$i] ) ){
    				if( $this->mappingTextLength[$i] >50 ){
    					$sheet->getColumnDimension($columnID)
    					->setWidth( 52);
    				}else{
    					if( $this->mappingTextLength[$i] >=0  && $this->mappingTextLength[$i] <=10){
    						$sheet->getColumnDimension($columnID)
    						->setWidth( 10);
    					}else{
    						$sheet->getColumnDimension($columnID)
    						->setAutoSize(true);
    					}
    				}
    			}
    		}
    	}
    	if( $createSheet == false ){
    		$sheet = $this->createSheetName($document, "data");
    	}
    	//guarda y envia el excel
    	if( $report_operation == self::REPORT_SEND ){
    		$this->saveExcel($document, $pathToFileOut,$nameFileOut);
    		$subject = '[FIELDVISION] Estadistica de tareas actuales';
    		$messageView = 'email.exportRegisters';
    		$this->info( "Genera reporte ".$pathToFileOut.$nameFileOut );
    		$this->sendEmail($to, $subject, $messageView, $pathToFileOut.$nameFileOut);
    	}else{
    		$this->downloadExcel($document,$nameFileOut);
    	}
    }
    
    /**
     * Escribe los datos del arreglo en la hoja de excel pasada
     * @param PHPExcel_Worksheet $sheet  hoja de excel a escribir
     * @param array $data  arrgelo de datos con los datos a escribir
     * @param int $row  fila en la que se empieza a escribir
     * @param string $path  path a escribir
     * @return number  numero de filas escritas
     */
    public function writeArrayToSheet( PHPExcel_Worksheet &$sheet, array $data, int $row=1, $path=""){
    	//dump("path: $path  row: $row",$data);
    	//$prof=[];
    	//ordenar primero por los que tengan valores y luego los que tengan arreglos
    	$data= $this->sortBytype($data);
    	foreach ($data as $key=>$value){
    		if(is_array($value)){
    			$prof2=0;
    			$count=0;
    			$indice=0;
    			$pathgenerated=$path;
    			if(is_numeric($key)){
    				$indice=$key;
    				$count=count($value);
    			}else{
    				$pathgenerated = $pathgenerated.".".$key;
    			}
    			
    			$newLine=$this->getMaxNumberByPath($this->prof,$pathgenerated);
    			//dump($this->prof);
    			$prof2=$this->writeArrayToSheet( $sheet, $value, $this->rowActual + $newLine ,$pathgenerated );//+$indice
    		}else{
    			$pathg= $path.".".$key;
    			if(key_exists($pathg, $this->mappingName)){
    				$col=$this->mappingName[$pathg];
    				if(key_exists($pathg, $this->prof)){
    					$this->prof[$pathg]= $this->prof[$pathg] +1;
    				}else{
    					$this->prof[$pathg]= 1;
    				}
    		
    				if( !isset($this->mappingTextLength[$col] ) ){
    					$this->mappingTextLength[$col]= strlen($value);
    				}else{
    					if(strlen($value) > $this->mappingTextLength[$col] ){
    						$this->mappingTextLength[$col]= strlen($value);
    					}
    				}
    				
    				
    				//$this->info("Imprime [".$row.",".$col."]".$value);
    				$sheet->setCellValueByColumnAndRow($col,$row,$value);
    				//$sheet->setCellValueByColumnAndRow($col,$row,$this->idx++);
    				
    			}else{
    				//$this->warn("NO existe ".$pathg);
    				//var_dump($this->mappingName);
    			}
    		}
    	} 
    	return $this->getMaxNumber($this->prof);
    }
    
    /**
    * Orderna por tipo primero los que sus valores no son arreglos y de ultimo los arreglos
    * @param array $array
    * @return array
    */
    public function sortBytype(array $array){
    	$arrayValues=[];
    	$arrayArrays=[];
    	foreach ($array as $key=>$value){
    		if( is_array( $value ) ){
    			if(is_numeric($key)){
    				array_unshift($arrayArrays, $value);
    			}else{
    				$arrayArrays[ $key ]= $value;
    			}
    		}else{
    			if(is_numeric($key)){
    				array_unshift($arrayValues, $value);
    			}else{
    				$arrayValues[ $key ]= $value;
    			}
    		}    		
    	}
    	$array=[];
    	foreach ($arrayValues as $key=>$value){
    		$array[$key]=$value;
    	}
    	foreach ($arrayArrays as $key=>$value){
    		$array[$key]=$value;
    	}
    	return $array;
    }
    
    /**
     * Busca el mayor valor de arreglo y lo retorna
     * @param array $array arreglo numerico
     * @return number
     */
    public function getMaxNumber( array $array ){
    	$result=0;
    	foreach ($array as $key=>$value){
    		if(is_numeric($value) && $value > $result){
    			$result = $value;
    		}
    	}
    	return $result;
    }
    
    
    /**
     * Busca el mayor valor de arreglo y lo retorna
     * @param array $array arreglo numerico
     * @param str $path
     * @return number
     */
    public function getMaxNumberByPath( array $array, $path="" ){
    	$result=0;
    	foreach ($array as $key=>$value){
    		if(is_numeric($value) && strlen( $key ) >= strlen( $path )   && substr($key, 0, strlen( $path ) ) === $path  && $value > $result){
    			$result = $value;
    		}
    	}
    	return $result;
    }
    
    //imprime la barra de encabezados
    public function writeHearders(PHPExcel_Worksheet &$sheet, $reportName="" , $filtersString="",int $totalColumns=8 ,$pathlogo=""){
    	$now= Carbon::now();
    	//impime los encabezados
    	if($totalColumns< 8){
    		$totalColumns=8;
    	}
    	
    	//logo
    	$dataLogoDetailsTask = array(
    			array("areamerge"=>"A1:C4", "areastyle"=>"A1:C4","style"=>$this->getStyleHeaders()  , "image"=>$pathlogo )
    	);
    	//datos de filtros
    	$columanFinalFltros = PHPExcel_Cell::stringFromColumnIndex( $totalColumns-3 );//ultima columan de  filtros
		//header de la fecha    	
    	$columanStrartDate = PHPExcel_Cell::stringFromColumnIndex( $totalColumns-2 );//columan final
    	$columanFinalDate = PHPExcel_Cell::stringFromColumnIndex( $totalColumns );//columan final
    	$dataTitleDetailTask = array(
    			array("areamerge"=>"D1:".$columanFinalFltros."1", "text"=>$reportName ),
    			array("areamerge"=>"D2:".$columanFinalFltros."4", "text"=> "".$filtersString ),
    			array("areastyle"=>"D1:".$columanFinalFltros."4", "style"=> $this->getStyleHeadersFilters() )
    	);
    	$dataFechaDetailTask = array(
    			array("areamerge"=>$columanStrartDate."1:".$columanFinalDate."4", "areastyle"=>$columanStrartDate."1:".$columanFinalDate."4", "text"=>$now->format('Y/m/d H:i:s'),"style"=>$this->getStyleHeaders())
    	);
    	 
    	$this->drawHeader($sheet, $dataLogoDetailsTask , $dataTitleDetailTask, $dataFechaDetailTask);
    }

    /**
     * mapea un arreglo en notacion punto
     * @param array $arr
     * @param string $path
     * @return Ambigous <multitype:string , multitype:>
     */
    public function mapArray(array $arr,$path=""){
    	$map=array();
    	foreach ($arr as $key => $value){
    		if(is_array($value)){
    			$map= array_merge($map,$this->mapArray($value,$path.".".$key));
    		}else{
    			$map[]=$path.".".$key;
    		}
    	}
    	return $map;
    }
    
    /**
     * Extrae los filtros en un solo nivel 
     * @param array $filters  filtros a aplicar
     * @param array $result resultado de afiltros en un solo nivel 
     */
    public function extractFilters(array $filters, array &$result){
    	foreach ($filters as $key => $value){
    		if( is_numeric( $key )){
    			array_push($result, $value);
    		}else{
    			if(is_array( $value)){
    				$this->extractFilters($value,$result);
    			}
    		}
    	}    	
    }
    
    /**
     * Genera los indices para los datos 
     * @param array $data
     * @param number $indexInit
     * @return multitype:number
     */
    public function generateIndexToPrint(array $data, $indexInit=1){
    	$result =array();
    	foreach ($data as $key => $value){
    		$result[$value]=$indexInit++;
    	}
    	return $result;
    	
    }
    
    /**
     * Convierte una notacion punto a 
     * @param string $str cadea en notacion punto  ejemplo recurso.empresa.nombre
     * @param unknown $vauleBase es el valor que va a quedar en la raiz
     * @return multitype:Ambigous <multitype:NULL , multitype:multitype:NULL  > |multitype:NULL  regresa un arreglo
     */
    public function arrayDot(string $str,$vauleBase=1){
    	
    	$dots= explode(".", $str,2);
    	$key=$dots[0];
    	if(count($dots)>1){
    		return array($key=>$this->arrayDot($dots[1],$vauleBase));	
    	}else{
    		return array($key=>$vauleBase);
    	}
    }

    /**
     * Genera un path del primer elemento del areglo en notacion punto
     * @param array $arr  ejemplo ["0"=>"a","1"=>"b","2"=>"c","3"=>"d","4"=>"e"]
     * @return str salida "a.b.c.d.e"
     */
    function toPath(array $arr){
    	$path= "";
    	//var_dump($arr);
    	$i=count($arr);
    	foreach( $arr as $value ){
    		$path .= $value;
    		if( $i>1 ){
    			$path .= ".";
    		}
    		$i--;
    	}
    	return $path;
    }
    
    /**
     * 
     * @param unknown $field  "a.b"
     * @param unknown $op operacion que va a manejar eq|gt|lt|gte|lte|bwte  ejemplo "eq"
     * @param unknown $value  valor del filtro ejemplo "Data"
     * @return array arreglo ejemplo ["a"=>["b"=>["operation"=>"eq","value"=>"Data"]]]
     */
    function generaArray($field,$op,$value){
    	$arreglo= explode (".", $field );
    	$rel=$arreglo[0];
    	if(count($arreglo) >1){
    		unset($arreglo[0]);
    		return array( $rel=>generaArray(toPath($arreglo),$op,$value));
    	}
    	return array($rel=>["operation"=>$op,"value"=>$value]);
    }
    
    /**
     * Verifica si es un arreglo de arreglos considerando que debe existir mas de un elemento
     * @param array $arr
     * @return boolean
     */
    public function isArayOfArray(array $arr){
    	$totalArray=0;
    	foreach ($arr as $key=>$value){
    		if(! is_array( $value )){
    			return false;
    		}
    		$totalArray++;
    	}
    	return $totalArray>1;
	}
    
    /**
     * Obtiene  la profundidad de un arreglo
     * @param array $arr
     * @return number
     */
    public function countProfundidad(array $arr){
    	$prof=0;
    	foreach ($arr as $key=> $value){
    		$prof2=0;
    		if( is_array( $value ) ){
    			if(is_numeric( $key )){
    				$prof2 = $this->countProfundidad( $value );
    			}else{
    				$prof2 = 1 + $this->countProfundidad( $value );
    			}    			
    			
    		}else{
    			if(!is_numeric( $key )){
    				$prof2 = 1 ;
    			} 
    		}
    		if($prof2 > $prof){
    			$prof=$prof2;
    		}
    	}
    	return $prof;
    }
    
    /**
     * Obtiene el stylo para poner bordes
     * @param bool $borderLeft
     * @param bool $borderRight
     * @param bool $borderVertical
     * @param bool $borderBottom
     * @param bool $borderTop
     * @param string $fillColor
     * @return multitype:multitype:string  string multitype:string multitype:string   multitype:multitype:string
     */
    public function getStyleBordersAndFillColor(bool $borderLeft=true,bool $borderRight=true,bool $borderVertical=true,bool $borderBottom=true,bool $borderTop=true,$fillColor="FFFFFF" ){
    	$borders=array();
    	if($borderLeft){
    		$borders['left']= array(
    						'style' => PHPExcel_Style_Border::BORDER_THIN,
    					);
    	}
    	if($borderRight){
    		$borders['right']= array(
    						'style' => PHPExcel_Style_Border::BORDER_THIN,
    					);
    	}
    	if($borderVertical){
    		$borders['vertical']= array(
    				'style' => PHPExcel_Style_Border::BORDER_THIN,
    		);
    	}
    	 
    	if($borderBottom){
    		$borders['bottom']= array(
    				'style' => PHPExcel_Style_Border::BORDER_THIN,
    		);
    	}
    	if($borderTop){
    		$borders['top']= array(
    				'style' => PHPExcel_Style_Border::BORDER_THIN,
    		);
    	}
    	return array(
    			'font' => array(
    					'name' => 'Arial',
    					'size' => '8',
    			),
    			'borders' => $borders,
    			'alignment' => array(
    					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
    					'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
    					'wrap' => true,
    			),
    			'fill' => array(
    					'type' => PHPExcel_Style_Fill::FILL_SOLID,
    					'startcolor' => array(
    							'argb' => $fillColor,
    					),
    			),
    			'type' => PHPExcel_Style_Fill::FILL_SOLID,
    			'startcolor' => array(
    					'rgb' => $fillColor
    			));
    }

    /**
     * Cuenta la cantidad de hojas o datos finales
     * @param array $arr
     * @return number
     */
    public function countAnchura(array $arr){
    	$ancho=0;
    	foreach ($arr as $key=> $value){
    		if( is_array( $value ) == false ){
    			$ancho ++;
    		}else{
    			$ancho =  $ancho + $this->countAnchura( $value );
    		}
    	}
    	return $ancho;
    }
    
    /**
     * escribe los header de un arreglo dinamicamente
     * @param PHPExcel_Worksheet $sheet  hoja de excel
     * @param array $data  arreglo a escribir
     * @param int $row fila inicial
     * @param integer $colm columan inicial
     * @param integer $width ancho del arreglo enviado
     * @param integer $height alto o profundidad del arreglo enviado
     */
    public function writeTitles(PHPExcel_Worksheet &$sheet, array $data, int $row=1, int $colm, $width, $height){
    	//dd($data,$row,$colm,$width,"profundidad ".$height);
    	$colmToWrite=$colm;
    	foreach ($data as $key=> $value){
    		//$this->info("termina header ". $key);
    		$title= trans($key);    		
    		if( is_array( $value ) ){
    			//$this->info("INICIA con ".$key);    	
    			//var_dump($value);
    			$widthArray= $this->countAnchura($value); //lo que debo unir
    			$sheet->setCellValueByColumnAndRow($colmToWrite,$row,trans("general.".$title));
    			if($widthArray >1){
    				$sheet->mergeCellsByColumnAndRow($colmToWrite,$row,( $colmToWrite + $widthArray -1 ),$row);
    				$sheet->getStyle( PHPExcel_Cell::stringFromColumnIndex( $colmToWrite ).$row.":".PHPExcel_Cell::stringFromColumnIndex( ( $colmToWrite + $widthArray -1 ) ).$row )
    				//$sheet->getStyleByColumnAndRow($colmToWrite,$row,( $colmToWrite + $widthArray -1 ),$row)
    				      ->applyFromArray( $this->getStyleHeaders() );
    			}else{
    				$sheet->mergeCellsByColumnAndRow($colmToWrite,$row,( $colmToWrite  ),$row);
    				
    				//dd( PHPExcel_Cell::stringFromColumnIndex( $colmToWrite ).$row,$title);
    				$sheet->getStyle( PHPExcel_Cell::stringFromColumnIndex( $colmToWrite ).$row.":".PHPExcel_Cell::stringFromColumnIndex( $colmToWrite ).$row )
    				      ->applyFromArray( $this->getStyleHeaders() );
    				
    			}
    			
    			//
    			$this->writeTitles( $sheet, $value, $row+1, $colmToWrite, $widthArray, $height-1 ); 
    			
    			$colmToWrite = $colmToWrite +$widthArray;
    		}else{//es una hoja
    			//$this->info(">>>col ".$colmToWrite." row ".$row." valor: ".$title. " merge ".$colmToWrite."-".$row." a ".$colmToWrite."-".( $row + $height ));
    			$sheet->setCellValueByColumnAndRow($colmToWrite,$row,trans("general.".$title));
    			$sheet->mergeCellsByColumnAndRow( $colmToWrite, $row, $colmToWrite, ( $row + $height ) );/// desde el row hasta la profundidad
    			//TODO aplicar el estylo PHPExcel_Cell::stringFromColumnIndex( $columnDataStart )
    			$sheet->getStyle( PHPExcel_Cell::stringFromColumnIndex( $colmToWrite).$row.":". PHPExcel_Cell::stringFromColumnIndex($colmToWrite). ( $row + $height ) )
    			      ->applyFromArray( $this->getStyleHeaders() );
    			$colmToWrite++;
    		}
    		
    	}
    	
    	
    }
	
    /**
     * Genera un nombre valido para una hoja de excel partiendo de un nombre base
     * @param string $name
     * @return string
     */
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
    
    /**
     * estilo para los datos de la tabla
     * @return multitype:multitype:string  multitype:multitype:string  string
     */
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
    
    /**
     * Estil para totalizar data
     * @return multitype:multitype:string boolean  multitype:multitype:string  string  multitype:multitype:string
     */
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
    
    
    /**
     * Estilo para los headers
     * @return multitype:multitype:string boolean  multitype:multitype:string  string  multitype:string  multitype:multitype:string
     */
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
    			)
		);
    }
    
    /**
     * estilo para los filtros
     * @return multitype:multitype:string boolean  multitype:multitype:string  string  multitype:boolean string  multitype:multitype:string
     */
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
		            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
		            'wrap' => true,
		        ),
    	);
    }
    
    /**
     * Obtiene el estilo del porcentaje
     * @return multitype:string
     */
    public function getFormatPercentage(){
    	return array( 
            'code' => PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE_00
        );
    }
    
    /**
     * obtiene el formato para moneda
     * @return string
     */
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
    
    /**
     * 
     * @param unknown $sheet hoja de excel
     * @param array $data [
     * 	areamerge = rango de celdas a combinar
     * 	text= texto a escribir
     *  image= imagen a escribir
     *  style= arreglo con el estilo a aplicar
     *  areastyle = rango donde se desea aplicar el estilo este sobre-escribe el estilo areamerge de estar presente
     *  <br> ningun campo es obligatorio 
     * ]
     */
    public function applicateStyle(&$sheet, array $data=array()){
    	if(is_array($data)){
    		//dd($data);
    		for($i=0; $i< count($data);$i++){
    			$customStyle=$data[$i];
    			$vcell= "A1";
		    	if(key_exists('areamerge', $customStyle)){
		    		$vcell2=explode(':', $customStyle['areamerge']);
		    		$vcell= $vcell2[0];
		    		//var_dump( $customStyle['areamerge'] );
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
    private function saveExcel(&$objXSL, $folderDestination, $nameFile="reporte.xlsx" ){
    	$objXSL->setActiveSheetIndex(0);    	
    	$objWriter = PHPExcel_IOFactory::createWriter($objXSL, 'Excel2007');
    	//para forzar las graficas
    	$objWriter->setIncludeCharts(true);    	
    	$filesalida=$folderDestination.'/'.$nameFile;
    	$objWriter->save( $filesalida );
    	return $filesalida;
    }
    
    /**
     *
     * @param unknown $objXSL
     * @param string $folderDestination
     * @param string $nameFile
     * @return string
     */
    private function downloadExcel(&$objXSL, $nameFile="reporte.xlsx" ){
    	// We'll be outputting an excel file
    	header('Content-type: application/vnd.ms-excel');
    	// It will be called file.xls
    	header('Content-Disposition: attachment; filename="'.$nameFile.'"');
    	$objXSL->setActiveSheetIndex(0);
    	$objWriter = PHPExcel_IOFactory::createWriter($objXSL, 'Excel2007');
    	//para forzar las graficas
    	$objWriter->setIncludeCharts(true);
    	// Write file to the browser
    	$objWriter->save('php://output');
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
            //Log::info($dataWeb);
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
