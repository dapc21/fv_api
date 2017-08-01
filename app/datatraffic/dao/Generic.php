<?php

namespace App\datatraffic\dao;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;

class Generic {
	
	function __construct()
	{
	
	}
	
	/**
	 * <p>Ingreso y borrado masivo de registros relacionados.</p>
	 *
	 * @access public
	 *
	 * @param $table, NOT_NULL, Nombre tabla pivote
	 * @param $fieldFather, NOT_NULL, Nombre campo padre
	 * @param $fieldSon, NOT_NULL,  Nombre campo hijo
	 * @param $fieldRecord, NOT_NULL, Nombre campo id de la tabla hijo
	 * @param $idFather, NOT_NULL, Identificador padre
	 * @param $idsSon, NOT_NULL, Identificadores registros hijo
	 * @param $sequence, NOT_NULL, Secuencia
	 * @param $idUserSession, NOT_NULL, Identificador usuario de sesión
	 *
	 * @return Json
	 */
	public function massivePivote($table, $fieldFather, $fieldSon, $fieldRecord, $idFather, $idsSon, $sequence, $idUserSession)
	{
		$deletedArray = array(
				'deleted'			=> true,
				'deleted_at'		=> Carbon::now(),
				'updated_at'		=> Carbon::now(),
				'id_user_update'	=> $idUserSession
		);
	
		$updateArray = array(
				'deleted'			=> false,
				'deleted_at'		=> null,
				'updated_at'		=> Carbon::now(),
				'id_user_update'	=> $idUserSession
		);
	
		
		
		if( !empty($idsSon[0]) ){
			// BORRADO LÓGICO
			DB::table($table)->where($fieldFather, $idFather)->whereNotIn($fieldSon, $idsSon)
			->whereNull('deleted_at')->update($deletedArray);
	
			// QUITAR BORRADO LÓGICO
			$newRecords = DB::table($table)->where($fieldFather, $idFather)->whereIn($fieldSon, $idsSon)
			->whereNotNull('deleted_at')->update($updateArray);
	
			$arrayIds = array();
	
			foreach( $idsSon as $idSon ){
				$identifier = $idSon['nreference'];
	
				$total = DB::table($table)->where($fieldFather, $idFather)
					->where($fieldSon, $identifier)->count();
	
				// valida que no exista relación en la base de datos
				if( $total == 0 ){
					$nextVal = DB::select(DB::raw($sequence));
	
					$insertArray = array(
							$fieldRecord		=> $nextVal[0]->nextval,
							$fieldFather		=> $idFather,
							$fieldSon			=> $identifier,
							'id_user_create'	=> $idUserSession,
							'updated_at'		=> Carbon::now(),
							'created_at'		=> Carbon::now()
					);
						
					// CREA RELACIÓN EN LA BASE DE DATOS
					DB::table($table)->insert($insertArray);
					$newRecords ++;
				}
			}
	
		} else {
				
			// BORRADO LÓGICO
			DB::table($table)->where($fieldFather, $idFather)
			->whereNull('deleted_at')->update($deletedArray);
	
			$newRecords = 0;
		}
	
		return $newRecords;
	}
	
	
	
	
	/**
	 * <p>Ingreso y borrado masivo de registros relacionados.</p>
	 *
	 * @access public
	 *
	 * @param $tableFather, NOT_NULL, Nombre de la  tabla pivote
	 * @param $fieldFather, NOT_NULL, Nombre campo padre
	 * @param $fieldSon, NOT_NULL,    Nombre de la llave ajena en el pivot(id que corresponde al padre)
	 * @param $fieldpivot, NOT_NULL, Nombre campo id de la tabla pivot
	 * @param $idFather, NOT_NULL, Identificador padre
	 * @param $idsSon, NOT_NULL, Identificadores registros hijo
	 * @param $sequence, NOT_NULL, Secuencia
	 * @param $idUserSession, NOT_NULL, Identificador usuario de sesión
	 *
	 * @return Json
	 */
	public function sincronizedPivot($tableFather, $fieldFather, $fieldSon, $fieldpivot, $idFather, Array $idsSon, $sequence, $idUserSession)
	{
		$deletedArray = array(
				'deleted'			=> true,
				'deleted_at'		=> Carbon::now(),
				'updated_at'		=> Carbon::now(),
				'id_user_update'	=> $idUserSession
		);
	
		$updateArray = array(
				'deleted'			=> false,
				'deleted_at'		=> null,
				'updated_at'		=> Carbon::now(),
				'id_user_update'	=> $idUserSession
		);
	
		if(! empty($idsSon[0]))
		{
			// BORRADO LÓGICO
			DB::table($tableFather)->where($fieldFather, $idFather)->whereNotIn($fieldSon, $idsSon)
			->whereNull('deleted_at')->update($deletedArray);
	
			// QUITAR BORRADO LÓGICO
			$newRecords = DB::table($tableFather)->where($fieldFather, $idFather)->whereIn($fieldSon, $idsSon)
			->whereNotNull('deleted_at')->update($updateArray);
	
			$arrayIds = array();
	
			foreach ($idsSon as $idSon)
			{
				$identifier = $idSon;
	
				$total = DB::table($tableFather)->where($fieldFather, $idFather)
				->where($fieldSon, $identifier)->count();
	
				// valida que no exista relación en la base de datos
				if($total == 0)
				{
					//$nextVal = DB::select(DB::raw($sequence));
					$nextVal = $this->getNexValSeq($sequence);
					//dd($nextVal, $identifier);
	
					$insertArray = array(
							$fieldpivot		=>  $nextVal,//$nextVal[0]->nextval,
							$fieldFather		=> $idFather,
							$fieldSon			=> $identifier,
							'id_user_create'	=> $idUserSession,
							'updated_at'		=> Carbon::now()->toDateTimeString(),
							'created_at'		=> Carbon::now()->toDateTimeString()
					);
					
					//dd($insertArray,$idFather);
	
					// CREA RELACIÓN EN LA BASE DE DATOS
					DB::table($tableFather)->insert($insertArray);
					$newRecords ++;
				}
			}
	
		} else {
	
			// BORRADO LÓGICO
			DB::table($tableFather)->where($fieldFather, $idFather)
			->whereNull('deleted_at')->update($deletedArray);
	
			$newRecords = 0;
		}
		
		return $newRecords;
	}
	
	
	
	public function getNexValSeq($sequencia){
		$sequencia ='SELECT '. $sequencia.'  FROM DUAL';
		$vid=DB::select(DB::raw( $sequencia ));
		$id=$vid[0]->nextval;
		return $id;
	}
	
	////////////////////////////////////////////################
	
	public function index($class, $filters = null, $sort = null,  $limit = null)
	{
		
		// Ordenamiento
		$sort		= json_decode($sort, true);
		$sort		= $sort[0];
		$field		= $sort['field'];
		$order		= $sort['order'];
		
		// filtros
		$filters	= json_decode($filters, true);
	
		// Limite paginación
		if(is_null($limit) || ! is_numeric($limit))
		{
			$limit = -1;
		}
		
		$info = $class->whereNested(function($query) use ($filters) 
		{
			// Filtros
			if(!is_null($filters))
			{
				foreach ($filters as $filter)
				{
					$data = false;
					
					switch ($filter['type'])
					{
						case 'string':
							$data = '%'.$filter['data'].'%';
							break;
						
						case 'int':
							$data = filter_var($filter['data'], FILTER_VALIDATE_INT);
							break;
							
						case 'boolean':
							$data = filter_var($filter['data'], FILTER_VALIDATE_BOOLEAN);
							break;
					}
					
					if($data != false)
					{
						$query->where($filter['field'], $filter['op'], $data);
					}
					continue;
				}
			}
			
		})->orderBy($field, $order)->paginate($limit);
		
		return  $info;
	}
	
	public function store($class, $properties = null)
	{
		$info = null;
		
		$properties	= json_decode($properties, true);
		
		if(!is_null($properties))
		{
			try{
		
				DB::beginTransaction();
			
				foreach ($properties as $property)
				{
					$class->$property['field'] = $property['data'];
				}
		
				$class->save();
		
				DB::commit();
				
				$info = $class;
			
			} catch(PDOException $exception) {
		
				DB::rollback();
				$info = "Error de transacción";
			}
		}
		return $info;
	}
	
	public function show($class, $id = null)
	{
		$info  = $class->find($idUser);
		
		return $info;
	}
}