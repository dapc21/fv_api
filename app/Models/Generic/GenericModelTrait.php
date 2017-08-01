<?php
namespace App\Models\Generic;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
//use \MongoDB\Operation\FindOneAndUpdate;

Trait GenericModelTrait {

    /**
     * Se definen las variables
     */


	/**
     * Obtener el proximo en la secuencia (MongoDB)
     */
    public function getNextSequence() {
        $collection = $this->getTable();

        $seq = DB::getCollection('counters')->findAndModify(
             array('_id' => $collection),
             array('$inc' => array('seq' => 1)),
             null,
             array('new' => true, 'upsert' => true)
        );

        return $seq['seq'];
    }

    /**
     * Asigna el id del Primary Key
     */
    public function assignPrimaryKeyId()
    {
        $strPrimaryKey = $this->primaryKey;

        //En caso que sea vacío se asigna
        if(empty($this->$strPrimaryKey))
            $this->$strPrimaryKey =  new \MongoDB\BSON\ObjectId();
    }

	/**
	 * return el nombre de la llave primaria del modelo
	*/
	public function getPrimaryKey(){
		//return $this->primaryKey;
        return '_id';
	}

    /**
	 * return el id de llave primaria del modelo
	*/
	public function getIdPrimaryKey(){
		//$strPrimaryKey = $this->primaryKey;
        //return $this->$strPrimaryKey;
        return $this->_id;
	}


	/**
	 * obtener el mapeo de columnas
	 * [
	 * ['aliasColumn'=>['nameColumn'=>'name','filtro'=>'filter|requiered']]
	 * ]
	 *
	 */

	/*public function getMapeoColumnas(){
		return $this->mapeo_columnas;
	}*/

	/**
	 * Obtener el mapeo de relaciones
	 * [
	 * 'nameRelation'=>['relation'=>'OneToMany','action'=>'create|update','id_relation'=>'id_relation','controller'=>'controllerModel'],
	 * 'nameRelation3'=>['relation'=>'ManyToMany','action'=>'create|update','id_relation'=>'id_relation','controller'=>'controllerModel']
	 * .....
	 * ]
	 *
	 *
	 */
	public function getMapeoRelaciones(){
		return $this->mapeo_relaciones;
	}



	public function getColumsIdentify(){
		return $this->colums_identify;
	}

	public function getColumsTitle(){
		return $this->colums_title;
	}

	public function getWhiteWith(){
		return $this->whiteWith;
	}

	//retorna los filtros correspondientes a un conjunto de datos
	public function getFilters(Array $a=array()){
		$filtros=array();
		//se obtiene  el mapeo de columnas
		$colum = $this->getColumnsMap();


		if(is_array($colum)){
			foreach ($colum as $key => $value ){
				if(is_array($value)){

					if(  array_key_exists('name', $value) &&  array_key_exists('filter', $value) ){

						//se obtinee el nombre real de la columna
						$realnamefiel=$value['name'];

						$realfilter=$value['filter'];

						if(in_array($realnamefiel, $a) ){
							$filtros[$realnamefiel]=$realfilter;
						}


					}
				}

			}
		}
		//dd($filtros);
		return $filtros;
	}

	//retorna el conjunto de  nombres de columnas mapeadas
	public function getNameColumns(){
		$columns=array();
		//se obtiene  el mapeo de columnas
		$colum = $this->getColumnsMap();
		if(is_array($colum)){
			foreach ($colum as $key => $value ){
				if(is_array($value)){
					//verifica que exista e
					if(array_key_exists('name', $value) ){
						//se obtinee el nombre real de la columna
						$realnamefiel=$value['name'];
						$columns[]=$realnamefiel;
					}
				}

			}
		}
		return $columns;
	}


	//retorna 'realnameColumn'=>'aliasColumn'
	public function getAliases(){
		$columns=array();
		//se obtiene  el mapeo de columnas
		$colum = $this->getMapeoColumnas();
		if(is_array($colum)){
			foreach ($colum as $key => $value ){
				if(is_array($value)){
					//verifica que exista e
					if(array_key_exists('name', $value) ){
						//se obtinee el nombre real de la columna
						$realnamefiel=$value['name'];
						$columns[$realnamefiel]=$key;
					}
				}

			}
		}
		return $columns;
	}

	/**
	public function getNexValSeq($sequencia){
		$sequencia ='SELECT '. $sequencia.'  FROM DUAL';
		$vid=DB::select(DB::raw( $sequencia ));
		$id=$vid[0]->nextval;
		return $id;
	}

        */



        //////////////////////////////////////////LO NUEVO//////////////////////

        /**Mapeo de Relaciones
        * recupera el mapeo d ralciones
        * [ 'vehiculos'=>[ 'type'=>'onetomany|onetomany|manytomany',
        *                  'foreign_controller' => 'App\Http\Controllers\ControllerApply',
        *                  'pivot_table' =>'public.test_vehiculo',
        *                  'pivot_id'=>'',
        *                  'pivot_id_parent'=>'id_empresa',
        *                  'pivot_id_foreign'=>'id_empresa'
         *                 ]
        * ]
        *
        * @return type array
        */
        public function getRelationshipMap(){
		return $this->relationship_map;
	}

        /**
         * Mapeo de Columnas
         * Recupera el mapeo de columnas
         * ['relation' =>['name'=>'id_tanblarelacional',
         *                'filter'=> 'expresiones de validacion requiered|numeric|exist() etc..',
         *                'editable'=> true|false ,
         *                'type'=>'numeric|string|timestamp'
         *               ]]
         *
         * @return type array
         */
        public function getColumnsMap(){
		return $this->columns_map;
	}

        /**
         *
         * @param string $sequencia
         * @return type
         */
	public function getNexValSeqp($sequencia){
		$sequencia ='SELECT nextval('. $sequencia.') as nextval';
		$vid=DB::select(DB::raw( $sequencia ));
		$id=$vid[0]->nextval;
		return $id;
	}

	/*
	 * Convierte Arreglo a DBRef
	 */
	public function changeArrToDBRef(&$matriz){
        foreach($matriz as $key => &$value){
            if (is_array($value)){
                if(\MongoDBRef::isRef($value)){
										$objMongoId = new \MongoId($value['$id']['$id']);
										$strCollections = json_decode($arguments['$ref']);
										$value = \MongoDBRef::create($strCollections, $objMongoId);
                }else{
                    $this->changeArrToDBRef($value);
                }
            }
        }
    }

    /*
     * Cambiamos los DBRef, Date y ObjectID a String
     */
    public function changeDataMongoToString(&$matriz)
    {
      foreach($matriz as $key => &$value){
        if (is_array($value)){
          if($this->bIsDBRef($value))
            $value = (string) $value['$id'];
          else
            $this->changeDataMongoToString($value);
        }else{
          if(is_a($value, '\MongoDB\BSON\ObjectID'))
            $value = (string) $value;
          elseif(is_a($value, '\MongoDB\BSON\UTCDateTime'))
            $value = $this->serializeDate($value->toDateTime());
        }
      }
    }

    /**
    * Verifica si es un DBRef
    */
    public function bIsDBRef($arrDBRef)
    {
      return (is_array($arrDBRef) &&
              array_key_exists('$ref', $arrDBRef) &&
              array_key_exists('$id', $arrDBRef) &&
              is_a($arrDBRef['$id'], '\MongoDB\BSON\ObjectID'));
    }


    /**
     * Get the model's relationships in array form.
     *
     * @return array
     */
    public function relationsToArray()
    {
        $attributes = [];

        foreach ($this->getArrayableRelations() as $key => $value) {
            // If the values implements the Arrayable interface we can just call this
            // toArray method on the instances which will convert both models and
            // collections to their proper array form and we'll set the values.
            if ($value instanceof Arrayable || !is_null($value)) {
                $relation = $value->toArray();
            }

            // If the value is null, we'll still go ahead and set it in this list of
            // attributes since null is used to represent empty relationships if
            // if it a has one or belongs to type relationships on the models.
            elseif (is_null($value)) {
                $relation = $value;
            }

            // If the relationships snake-casing is enabled, we will snake case this
            // key so that the relation attribute is snake cased in this returned
            // array to the developers, making this consistent with attributes.
            /*if (static::$snakeAttributes) {
                $key = Str::snake($key);
            }*/

            // If the relation value has been set, we will set it on this attributes
            // list for returning. If it was not arrayable or null, we'll not set
            // the value on the array because it is some type of invalid value.
            if (isset($relation) || is_null($value)) {
                $attributes[$key] = $relation;
            }

            unset($relation);
        }

        return $attributes;
    }

    /**
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        $attributes = $this->attributesToArray();

        //Procesamos la data de mongo(DBRef, ObjectId y Date)
        $this->changeDataMongoToString($attributes);

        $attributesRelations = $this->relationsToArray();

        //Procesamos la data de mongo(DBRef, ObjectId y Date)
        $this->changeDataMongoToString($attributesRelations);

        return array_merge($attributes, $attributesRelations);
    }
	
	public function getValidationRules($method, $id_company)
	{
		return $this->validation_rules;
	}
	
	public function getDBRef()
	{
		$strNameCollection = $this->getTable();
		$strId = $this->getIdPrimaryKey();
		$insObjectID = new \MongoDB\BSON\ObjectId($strId);
		
		return ['$ref' => $strNameCollection, '$id' => $insObjectID];		
	}
	
	/**
	 * exporta el modelo actual a un array
	 * @param array $attributes ['login'=>1,'company'=>['name':1]]
	 * @return multitype:multitype: NULL
	 */
	public function toArrayExcel(array $attributes ) {
		$result= array();
		foreach ($attributes as $key => $value){
			if(key_exists($key, $this->relationship_map)){
				$relatedModels = $this->$key;
	
				if($relatedModels instanceof Collection || is_array($relatedModels))
				{
					$result[$key] = [];
					foreach ($relatedModels as $relatedModel)
					{
						//Si es un DBRef entonces
						if(is_array($relatedModel)) {
							$relation = $this->$key();
							$query = $relation->getRelated();
							$relatedModel = $query->where('_id','=',$relatedModel['$id'])->first();
						}

						if($relatedModel) {
							$result[$key][] = $relatedModel->toArrayExcel($value);
						}
					}
				}
				else
				{	if( !empty( $relatedModels )){
						$result[$key]= $relatedModels->toArrayExcel($value);
					}	
				}
	
			}else{
				$result[$key]= $this->$key;
			}
		}
		return $result;
	}
	
	
	/**
	 *
	 * @param array $filters  ["a"=>["b"=>["operation"=>"eq","value"=>"Data"]],"a"=>["id_c"=>["operation"=>"in","value"=>"["356612024702450","696612064702452"]"]]]
	 */
	public function getFiltersAsString(array $filters,$path, $fieldName= "_id", array &$resultData ){
	   $result= array();
	   
	   if(key_exists("operation",$filters) && key_exists("value",$filters)){
	   		$result= array("filter"=>$this->where($fieldName,$filters["value"])->first()->name,"path"=>$path);
	   		array_push($resultData, $result);
	   		return $result;
	   }
	   
	   foreach ($filters as $key => $value){
	   	//se verifica si existe en el modelo
	   	$keyWithoutId = preg_replace("/^id_/i", "$1", $key);
	   	if(key_exists($keyWithoutId, $this->relationship_map)){
	   		$vcontroller= $this->relationship_map[$keyWithoutId];
	   		$controller = new $vcontroller['foreign_controller']();
	   		$relatedModel = $controller->getModelo();
	   		$relatedModel = new $relatedModel();
	   		$keyreturn=$key;
	   		if (preg_match("/id\_/i", $key)) {
	   			$keyreturn="_id";
	   		}
	   		$result[$key]=$relatedModel->getFiltersAsString($value,$this->addToPath($keyWithoutId,$path),$keyreturn,$resultData);
	   	}else {
	   		
	   		if(strcmp($key, "deleted_at")!=0 &&  strcmp($key, "created_at")!=0 &&  strcmp($key, "created_at")!=0){
	   			if( strcmp($key, $keyWithoutId)==0){
			   		if(is_array($value) && key_exists("operation", $value)  ){
			   			switch ($value['operation']){
			   				case "btw":
			   					$valueBtw=$value['value'];
			   					$result[$key]=array("filter"=>$valueBtw[0]." - ".$valueBtw[1] ,"path"=>$this->addToPath($key,$path));
			   					break;
			   				case "btwe":
			   					$valueBtw=$value['value'];
			   					$result[$key]=array("filter"=>$valueBtw[0]." - ".$valueBtw[1] ,"path"=>$this->addToPath($key,$path));
			   					break;
			   				case "in":
			   					$valueIn=$value['value'];
			   					$inValue="";
			   					if(is_array( $valueIn )){
			   						foreach ($valueIn as $kIn => $kValue ){
			   							$inValue .= "(".$kValue.") ";
			   						}
			   					}else{
			   						$inValue=$valueIn;
			   					}
			   					$result[$key]=array("filter"=>$inValue ,"path"=>$this->addToPath($key,$path));
			   					
			   					break;
			   				case "notin":
			   					$valueIn=$value['value'];
			   					$inValue="";
			   					if(is_array( $valueIn )){
			   						foreach ($valueIn as $kIn => $kValue ){
			   							$inValue .= "(".$kValue.") ";
			   						}
			   					}else{
			   						$inValue=$valueIn;
			   					}
			   					$result[$key]=array("filter"=>$inValue ,"path"=>"no".$path.".".$key);
			   					break;
			   				
			   				default:
			   					$result[$key]=array("filter"=>$value["value"],"path"=>$this->addToPath($key,$path));
			   					break;
			   		    }
			   		    array_push($resultData, $result[$key]);
			   		}
	   		    }else{
	   		    	switch ($value['operation']){
	   		    		case "eq":
	   		    			$valueEq=$value['value'];
	   		    			$dataModel= $this->where($key, $valueEq)->first();
	   		    			if( !is_null($dataModel) ){
	   		    				$result[$key]=array("filter"=>$dataModel ,"path"=>$this->addToPath($key,$path));
	   		    				array_push($resultData, array("filter"=>$dataModel ,"path"=>$this->addToPath($key,$path)) );
	   		    			}	   		    		
	   		    			break;
	   		    		case "in":
	   		    			$valueIn=$value['value'];
	   		    			$inValue="";
	   		    			$dataModel= $this->whereIn($key, $valueEq)->get();
	   		    			if( !is_null($dataModel)  ){
	   		    				if($dataModel instanceof Collection){
	   		    					$inValue="[";
	   		    					foreach ($dataModel as $modelo){
	   		    						$inValue .= "(";
	   		    						$atts = $modelo->toArray();
	   		    						foreach ($atts as $keyAtt => $valAtt){
	   		    							$inValue .= $valAtt."  " ;
	   		    						}
	   		    						$inValue .= ") ";
	   		    						
	   		    						
	   		    					}
	   		    					$inValue .= "]";
	   		    				}
	   		    				
	   		    			}
	   		    			$result[$key]=array("filter"=>$inValue ,"path"=>$this->addToPath($key,$path));	
	   		    			array_push($resultData, array("filter"=>$inValue ,"path"=>$this->addToPath($key,$path)) );
	   		    			break;
	   		    		case "notin":
	   		    			$valueIn=$value['value'];
	   		    			$inValue="";
	   		    			$dataModel= $this->whereIn($key, $valueEq)->get();
	   		    			if( !is_null($dataModel)  ){
	   		    				if($dataModel instanceof Collection){
	   		    					$inValue="[";
	   		    					foreach ($dataModel as $modelo){
	   		    						$inValue .= "(";
	   		    						$atts = $modelo->toArray();
	   		    						foreach ($atts as $keyAtt => $valAtt){
	   		    							$inValue .= $valAtt."  " ;
	   		    						}
	   		    						$inValue .= ") ";
	   		    						
	   		    						
	   		    					}
	   		    					$inValue .= "]";
	   		    				}
	   		    				
	   		    			}
	   		    			$result[$key]=array("filter"=>$inValue ,"path"=>"no.".$this->addToPath($key,$path));
	   		    			array_push($resultData, array("filter"=>$inValue ,"path"=>"no.".$this->addToPath($key,$path)));
	   		    			break;
	   		    	
	   		    		default:
	   		    			
	   		    			break;
	   		    	}
	   		    }
	   		}
	   	}
	   }
	   return $result;	
	}
	
	/**
	 * 
	 * @param string $value
	 * @param string $path
	 * @return string
	 */
	private function addToPath(string $value,$path=""){
		if( !empty($path)){
			return $path.".".$value;
		}else{
			return $value;
		}
	}
}
