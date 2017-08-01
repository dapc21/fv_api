<?php
namespace App\datatraffic\validation;

/**
 * HandlerValidator incorpora validaciones personalizadas
 * para que puedan ser utilizadas en laravel
 *
 * @package  app\datatraffic\validation\
 * @author   Nestor Orlando R. <nestorrojas@datatraffic.com.co>
 * @author   Juan Caimilo D. <juandiaz@datatraffic.com.co>
 * @version  1.0
 * @access   public
 */

use Illuminate\Validation\Validator;
use Illuminate\Support\MessageBag;
use Symfony\Component\Translation\TranslatorInterface;


class HandlerValidator extends Validator {
	
	public function __construct(TranslatorInterface $translator, $data, $rules, $messages = array())
	{
		parent::__construct($translator, $data, $rules, $messages);
	
		// Agrega regla implicita a laravel
		$this->implicitRules[] = array('SeqVal', 'Json', 'ExistsWith', 'UniqueWith');
	}

	/**
	 * Cambia a formato estandar laravel
	 * 
	 * @param array $parameters
	 * @return mixed
	 */
	private function parameterFormat($parameters)
	{
		return str_replace(array(';', '+'), array(':', ','), $parameters);
	}
	
	/**
	 * Ejecuta validaciones dependiendo si se cumple la validación anterior
	 * 
	 * Ex. 'field' => 'order_val:integer,max;2,unique;users+id'
	 * 
	 * @access protected
	 * 
	 * @param string	$attribute
	 * @param mixed 	$value
	 * @param array		$parameters
	 * 
	 * @return boolean
	 */
	protected function validateSeqVal($attribute, $value, $parameters)
	{
		$rules = $this->parameterFormat($parameters);
		
		$nroVal = count($this->messages->all()) + 1;
		
		if (count($rules))  foreach ($rules as $rule)
		{
			$this->rules[$attribute] += array($rule);
			
			$this->validate($attribute, $rule);
			
			// Si no se cumple la primera validación no se procesa la siguiente
			if(count($this->messages->all()) === $nroVal)
			{
				break;
			}	
		}
		
		return  true;
	}
	
	/**
	 * Determina si una cadena tiene formato json.
	 * 
	 * @param string	$attribute
	 * @param mixed		$value
	 * @param array		$parameters
	 * 
	 * @return boolean
	 */
	protected function validateJson($attribute, $value, $parameters)
	{
		if (!is_string($value))
		{
			return false;
		}
		
	    // trim white spaces
	    $string = trim($value);
	    
	    // get first character
	    $firstChar = substr($value, 0, 1);
	    
	    // get last character
	    $lastChar = substr($value, -1);
	    
	    // check if there is a first and last character
	    if (!$firstChar || !$lastChar)
	    {
	        return false;
	    }
	    
	    // make sure first character is either { or [
	    if ($firstChar !== '{' && $firstChar !== '[') 
	    {
	        return false;
	    }
	    
	    // make sure last character is either } or ]
	    if ($lastChar !== '}' && $lastChar !== ']') 
	    {
	        return false;
	    }
	    
	    // let's leave the rest to PHP.
	    // try to decode string
	 	//intenta decodificar eljson
		$js = json_decode($value, true);
		
	    // check if error occurred
	    $isValid = json_last_error() === JSON_ERROR_NONE;
		
		return $isValid;
	}
	
	/**
	 * Consulta en la base de datos si existe un registro teniendo en cuenta
	 * datos correspondientes de otros campos validados.
	 * 
	 * @param string	$attribute
	 * @param mixed		$value
	 * @param array		$parameters
	 * 
	 * @return boolean
	 */
	protected function validateExistsWith($attribute, $value, $parameters)
	{
		$failures = $this->failed();
		
		// primer parametro nombre de la tabla
		$table = array_shift($parameters);
		
		// Segundo parametro campo identificador
		$idField = array_shift($parameters);
		
		$arrayData = array();
		$data = null;
		
		foreach($parameters as $key => $parameter)
		{
			$data = $parameter;
			
			if(($key % 2) != 0)
			{
				if(! is_null($data = array_get($this->data, $parameter)))
				{
					if(array_key_exists($parameter, $failures))
					{
						return false;
					}
				} else {
					$data = $parameter;
				}
			}
			$arrayData[] = $data;
		}
		
		array_unshift($arrayData, $table, $idField);
		
		return $this->validateExists($attribute, $value, $arrayData);
	}
	
	/**
	 * Consulta la base de datos en busqueda de registros existentes con los
	 * mismos datos.
	 *
	 * @param  string	$attribute
	 * @param  mixed 	$value
	 * @param  array	$parameters
	 * 
	 * @return boolean
	 */
	public function validateUniqueWith($attribute, $value, $parameters)
	{
		$failures = $this->failed();
		 
	
		// primer parametro nombre de la tabla
		$table = array_shift($parameters);
	
		// Segundo parametro campo identificador
		$mainField = array_shift($parameters);
		 
		$extra	= array();
		$data	= null;
		$field	= null;
	
		foreach($parameters as $key => $parameter)
		{
			if(($key % 2) != 0)
			{
				if(! is_null($data = array_get($this->data, $parameter)))
				{
					if(array_key_exists($parameter, $failures))
					{
						return false;
					}
				} else {
					 
					$data = $parameter;
				}
			} else {
	
				$field =  $parameter;
			}
			 
			$extra[$field] = $data;
		}
	
		$verifier = $this->getPresenceVerifier();
	
		return $verifier->getCount($table, $mainField, $value, null, null, $extra) == 0;
	}
	
	/**
	 * Control mensaje correspondiente a la validación exists_with
	 * 
	 * @param string	$message
	 * @param string	$attribute
	 * @param mixed		$rule
	 * @param array		$parameters
	 * 
	 * @return mixed
	 */
	public function replaceExistsWith($message, $attribute, $rule, $parameters)
	{
		return str_replace(':attribute', $attribute, $message);
	}
	
	/**
	 * Control mensaje correspondiente a la validación unique_with
	 *
	 * @param string	$message
	 * @param string	$attribute
	 * @param mixed		$rule
	 * @param array		$parameters
	 *
	 * @return mixed
	 */
	public function replaceUniqueWith($message, $attribute, $rule, $parameters)
	{
		return str_replace(':attribute', $attribute, $message);
	}
}