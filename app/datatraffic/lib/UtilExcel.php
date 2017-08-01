<?php
namespace App\datatraffic\lib;

use \PHPExcel;
use \PHPExcel_IOFactory;
use \PHPExcel_Worksheet;
use \PHPExcel_Cell;
use \PHPExcel_Worksheet_MemoryDrawing;
use \PHPExcel_Style_Border;
use \PHPExcel_Style_Fill;
use \PHPExcel_Style_NumberFormat;
use \PHPExcel_Style_Alignment;
use Carbon\Carbon;

class UtilExcel
{
    
    
    public function __construct() {
        
    }
    
    /**
     * Crea un documento de Excel
     * @return \PHPExcel
     */
    public static function createDocExcel() {
    	$objXSL = new PHPExcel();
    	return $objXSL;
    }
    
    /**
     * Crea y adiciona una hjoja de excel
     * @param \PHPExcel $objXSL
     * @param string $nameSheet
     * @return \PHPExcel_Worksheet
     */
    public static function createSheetName( \PHPExcel &$objXSL,$nameSheet ){
    	$myWorkSheet = new PHPExcel_Worksheet($objXSL, $nameSheet);
    	// Attach the "My Data" worksheet as the first worksheet in the PHPExcel object
    	$objXSL->addSheet($myWorkSheet); 
    	return $myWorkSheet;
    }
    
    
    /**
     * Estilo para los headers
     * @return multitype:multitype:string boolean  multitype:multitype:string  string  multitype:string  multitype:multitype:string
     */
    public static function getStyleHeaders(){
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
     * Obtiene el stylo para poner bordes
     * @param bool $borderLeft
     * @param bool $borderRight
     * @param bool $borderVertical
     * @param bool $borderBottom
     * @param bool $borderTop
     * @param string $fillColor
     * @return multitype:multitype:string  string multitype:string multitype:string   multitype:multitype:string
     */
    public static function getStyleBordersAndFillColor(bool $borderLeft=true,bool $borderRight=true,bool $borderVertical=true,bool $borderBottom=true,bool $borderTop=true,$fillColor="FFFFFF" ){
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
     * estilo para los filtros
     * @return multitype:multitype:string boolean  multitype:multitype:string  string  multitype:boolean string  multitype:multitype:string
     */
    public static function getStyleHeadersFilters(){
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
     * 
     * @param PHPExcel_Worksheet $sheet
     * @param string $reportName  nombre del reporte
     * @param string $filtersString  filtros 
     * @param int $totalColumns total columnas a imprimir
     * @param string $pathlogo  ubicacion del logo
     */
    public static function writeHearders(PHPExcel_Worksheet &$sheet, $reportName="" , $filtersString="",int $totalColumns=8 ,$pathlogo=""){
    	$now= Carbon::now();
    	//impime los encabezados
    	if($totalColumns< 8){
    		$totalColumns=8;
    	}
    	 
    	//logo
    	$dataLogoDetailsTask = array(
    			array("areamerge"=>"A1:C4", "areastyle"=>"A1:C4","style"=>self::getStyleHeaders()  , "image"=>$pathlogo )
    	);
    	//datos de filtros
    	$columanFinalFltros = PHPExcel_Cell::stringFromColumnIndex( $totalColumns-3 );//ultima columan de  filtros
    	//header de la fecha
    	$columanStrartDate = PHPExcel_Cell::stringFromColumnIndex( $totalColumns-2 );//columan final
    	$columanFinalDate = PHPExcel_Cell::stringFromColumnIndex( $totalColumns );//columan final
    	$dataTitleDetailTask = array(
    			array("areamerge"=>"D1:".$columanFinalFltros."1", "text"=>$reportName ),
    			array("areamerge"=>"D2:".$columanFinalFltros."4", "text"=> "".$filtersString ),
    			array("areastyle"=>"D1:".$columanFinalFltros."4", "style"=> self::getStyleHeadersFilters() )
    	);
    	$dataFechaDetailTask = array(
    			array("areamerge"=>$columanStrartDate."1:".$columanFinalDate."4", "areastyle"=>$columanStrartDate."1:".$columanFinalDate."4", "text"=>$now->format('Y/m/d H:i:s'),"style"=>self::getStyleHeaders())
    	);
    
    	self::drawHeader($sheet, $dataLogoDetailsTask , $dataTitleDetailTask, $dataFechaDetailTask);
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
    public static function writeTitles(PHPExcel_Worksheet &$sheet, array $data, int $row=1, int $colm, $width, $height){
    	//dd($data,$row,$colm,$width,"profundidad ".$height);
    	$colmToWrite=$colm;
    	foreach ($data as $key=> $value){
    		$title= trans($key);
    		if( is_array( $value ) ){
    			//var_dump($value);
    			$widthArray= self::countAnchura($value); //lo que debo unir
    			$sheet->setCellValueByColumnAndRow($colmToWrite,$row,$title);
    			if($widthArray >1){
    				$sheet->mergeCellsByColumnAndRow($colmToWrite,$row,( $colmToWrite + $widthArray -1 ),$row);
    				$sheet->getStyle( PHPExcel_Cell::stringFromColumnIndex( $colmToWrite ).$row.":".PHPExcel_Cell::stringFromColumnIndex( ( $colmToWrite + $widthArray -1 ) ).$row )
    				//$sheet->getStyleByColumnAndRow($colmToWrite,$row,( $colmToWrite + $widthArray -1 ),$row)
    				->applyFromArray( self::getStyleHeaders() );
    			}else{
    				$sheet->mergeCellsByColumnAndRow($colmToWrite,$row,( $colmToWrite  ),$row);
    
    				//dd( PHPExcel_Cell::stringFromColumnIndex( $colmToWrite ).$row,$title);
    				$sheet->getStyle( PHPExcel_Cell::stringFromColumnIndex( $colmToWrite ).$row.":".PHPExcel_Cell::stringFromColumnIndex( $colmToWrite ).$row )
    				->applyFromArray( self::getStyleHeaders() );
    
    			}
    			 
    			//
    			self::writeTitles( $sheet, $value, $row+1, $colmToWrite, $widthArray, $height-1 );
    			 
    			$colmToWrite = $colmToWrite +$widthArray;
    		}else{//es una hoja
    			$sheet->setCellValueByColumnAndRow($colmToWrite,$row,$title);
    			$sheet->mergeCellsByColumnAndRow( $colmToWrite, $row, $colmToWrite, ( $row + $height ) );/// desde el row hasta la profundidad
    			//TODO aplicar el estylo PHPExcel_Cell::stringFromColumnIndex( $columnDataStart )
    			$sheet->getStyle( PHPExcel_Cell::stringFromColumnIndex( $colmToWrite).$row.":". PHPExcel_Cell::stringFromColumnIndex($colmToWrite). ( $row + $height ) )
    			->applyFromArray( self::getStyleHeaders() );
    			$colmToWrite++;
    		}
    
    	}    	 
    }
    
    
    /**
     * Genera un nombre valido para una hoja de excel partiendo de un nombre base
     * @param string $name
     * @return string
     */
    public static function generateNameSheet( $name="NuevaHoja" ){
    	$name=preg_replace('/[^A-Za-z0-9\-]/', '', $name);
    	return substr($name, 0,30);
    }
    
    /**
     * Cuenta la cantidad de hojas o datos finales
     * @param array $arr
     * @return number
     */
    public static function countAnchura(array $arr){
    	$ancho=0;
    	foreach ($arr as $key=> $value){
    		if( is_array( $value ) == false ){
    			$ancho ++;
    		}else{
    			$ancho =  $ancho + self::countAnchura( $value );
    		}
    	}
    	return $ancho;
    }
    
    /**
     * Obtiene  la profundidad de un arreglo
     * @param array $arr
     * @return number
     */
    public static function countProfundidad(array $arr){
    	$prof=0;
    	foreach ($arr as $key=> $value){
    		$prof2=0;
    		if( is_array( $value ) ){
    			if(is_numeric( $key )){
    				$prof2 = self::countProfundidad( $value );
    			}else{
    				$prof2 = 1 + self::countProfundidad( $value );
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
     * @param unknown $sheet hoja de trabajo
     * @param unknown $dataLogo  [["areamerge"=>"A1:C1", "areastyle"=>'A1:D10', "text"=>"Not found","style"=>[], "image"=>"/path/image.jpg"] ... ["areamerge"=>"A1:C1", "areastyle"=>'A1:D10', "text"=>"Not found","style"=>[], "image"=>"/path/image.jpg"]]
     * @param unknown $dataTitle [["areamerge"=>"A1:C1", "areastyle"=>'A1:D10', "text"=>"Not found","style"=>[], "image"=>"/path/image.jpg"] ... ["areamerge"=>"A1:C1", "areastyle"=>'A1:D10', "text"=>"Not found","style"=>[], "image"=>"/path/image.jpg"]]
     * @param unknown $dataFecha [["areamerge"=>"A1:C1", "areastyle"=>'A1:D10', "text"=>"Not found","style"=>[], "image"=>"/path/image.jpg"] ... ["areamerge"=>"A1:C1", "areastyle"=>'A1:D10', "text"=>"Not found","style"=>[], "image"=>"/path/image.jpg"]]
     */
    public static function drawHeader(&$sheet, $dataLogo,$dataTitle, $dataFecha){
    	 
    	self::applicateStyle($sheet,$dataLogo);
    	self::applicateStyle($sheet,$dataTitle);
    	self::applicateStyle($sheet,$dataFecha);
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
    public static function applicateStyle(&$sheet, array $data=array()){
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
    						self::insetImageJpg($sheet,$customStyle['image'], 50, 50 ,$vcell);
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
    
    /**
     * 
     * @param unknown $sheet hoja de excel
     * @param unknown $path
     * @param unknown $height
     * @param unknown $width
     * @param string $celda
     */
    public static function insetImageJpg(&$sheet,$path, $height, $width ,$celda='A1'){
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
	
    /**
     *
     * @param unknown $objXSL
     * @param string $folderDestination
     * @param string $nameFile
     * @return string
     */
    public static function saveExcel(&$objXSL, $folderDestination, $nameFile="reporte.xlsx" ){
    	$objXSL->setActiveSheetIndex(0);
    	$objWriter = PHPExcel_IOFactory::createWriter($objXSL, 'Excel2007');
    	//para forzar las graficas
    	$objWriter->setIncludeCharts(true);
    	$filesalida=$folderDestination.'/'.$nameFile;
    	$objWriter->save( $filesalida );
    	return $filesalida;
    }
    
}

