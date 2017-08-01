<?php
namespace App\Http\Controllers\GenericMongo;

use App\Http\Controllers\Controller;

class GramarController extends Controller {

   //Operadores a usar para las comparaciones
   public static $operadores= array(
   		'eq'	=> '=',
   		'lt'	=> '<',
   		'gt'	=> '>',
   		'lte'	=> '<=',
   		'gte'	=> '>=',
   		'lk'	=> 'regex',
   		'btw'	=> 'between',
   		'dif'	=> '<>',
	   	'in'	=> 'in',
	   	'notin'	=> 'notin',
//No se usa    		'apt'	=> 1,
//No se usa   		'null'	=> 0,
		'isnull'=>'is null',//new
		'isnotnull'=>'is not null',//new
		'btwe'	=> 'between',
   );
   
   //tipo de datos y operadores que soporta
   public static $tipo_dato=array(
   		'numeric'=>array('eq','lt','gt','lte','gte','btw', 'dif', 'notnull', 'null'),
   		'boolean'=>array('eq', 'apt', 'notnull', 'null'),
   		'date'=>array('eq','lt','gt','lte','gte','btw', 'dif', 'notnull', 'null'),
   		'timestamp'=>array('eq','lt','gt','lte','gte','btw', 'dif', 'notnull', 'null'),
   		'string'=>array('eq','lt','gt','lte','gte','btw','lk', 'dif', 'notnull', 'null'),
   );

}