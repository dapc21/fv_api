<?php

/**
 * Describir la clase según PHPDOC
 */

namespace App\Http\Controllers\GenericMongo;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\datatraffic\lib\Util;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Input;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDateTime;
use Validator;

trait ControllerTraitStore
{
    /**
     * Función principal encargado de guardar los datos enviados
     */
    public function store(Request $request)
    {
        //DB::connection()->enableQueryLog();

        $error = true;
        $msg = trans('general.GENERAL_ERROR');
        $total = 0;
        $data = [];
        $view = null;
        $intCode = 402;

        $json = $request->getContent();
        $arrayFromJson = json_decode($json, true);

        $idUserCreated = $this->dGetUserDBRefSession();
        
        $this->beforeStore( $arrayFromJson );
        $insModelInsert = $this->storeModelFromArray($arrayFromJson, $idUserCreated, true);

        $relatedModel = new $this->modelo();
        $primaryKeyName = $relatedModel->getPrimaryKey();
        $id = $insModelInsert[$primaryKeyName]->__toString();
		
        $this->afterStore( $id );
        
        $error = false;
        $msg = trans('general.MSG_OK');
        $data = ["reference" => $id];
        $total = 1;
        $intCode = 201;

        $result = Util::outputJSONFormat($error, $msg, $total, $data, $view);

        //dump(DB::connection()->getQueryLog());

        return response($result, $intCode);
    }

    /**
     * Función que guardar genérica
     */
    public function storeModelFromArray($dataArray, $idUserCreated, $shouldISave = false)
    {
        $model = new $this->modelo();
        $relationshipMap = $model->getRelationshipMap();
        
        //Obtener atributos personalizados (id_company)
        $customAtributes = $this->getCustomAttributes($dataArray);

        //Usamos array_merge porque: Si los arrays de entrada tienen las mismas claves de tipo string, el último valor para esa clave sobrescribirá al anterior.
        $dataArray = array_merge($dataArray, $customAtributes);

        //Recuperamos id_company si existe
        $id_company = null;
        if(array_key_exists('id_company',$dataArray)) {
            $id_company = $dataArray['id_company'];
        }

        //Validar si se debe guardar
        if($shouldISave) {
            $validation_rules = $model->getValidationRules('CREATE', $id_company);
            if (!empty($validation_rules)) {
                $validator = Validator::make($dataArray, $validation_rules);

                if ($validator->fails()) {
                    throw new ValidationException($validator);
                }
            }
        }

        $result = [];
        $primaryKeyName = $model->getPrimaryKey();
        $result[$primaryKeyName] = new \MongoDB\BSON\ObjectId();

        foreach($dataArray as $key => $value)
        {
            $keyWithoutId = preg_replace("/^id_/", "$1", $key);

            if(array_key_exists($keyWithoutId,$relationshipMap))
            {
                $relatedController = new $relationshipMap[$keyWithoutId]['foreign_controller']();
                switch ($relationshipMap[$keyWithoutId]['type'])
                {
                    case'onetoone':
                        $result[$key] = $relatedController->storeOneToOne($value, $relatedController, $idUserCreated);
                        break;
                    case'embedsone':
                        $result[$keyWithoutId] = $relatedController->storeEmbsOne($value, $relatedController, $idUserCreated);
                        break;
                    case'embedsmany':
                        $result[$keyWithoutId] = $relatedController->storeEmbsMany($value, $relatedController, $idUserCreated);
                        break;
                    case'manytomany':
                        $result[$keyWithoutId] = $relatedController->storeManyToMany($value, $relatedController, $idUserCreated);
                        break;
                }
            }
            else
            {
                $modelDates = $model->getDates();
                if(in_array($key,$modelDates)) {
                    $result[$key] = new UTCDatetime(Carbon::createFromFormat('Y-m-d H:i:s', $value)->getTimestamp() * 1000);
                }
                else {
                    if($key === '_id'){
                        $value = new ObjectID($value);
                    }

                    $result[$key] = $value;
                }
            }
        }

        $result['id_user_create'] = $idUserCreated;
        $result['created_at'] = new UTCDateTime(Carbon::now()->getTimestamp() * 1000);
        $result['updated_at'] = new UTCDateTime(Carbon::now()->getTimestamp() * 1000);

        if($shouldISave)
        {
            DB::Collection($model->getTable())->insert($result);
        }

        return $result;
    }

    private function storeOneToOne($data, $relatedController, $idUserCreated)
    {
        if(is_array($data))
        {
            $result = $relatedController->storeModelFromArray($data,$idUserCreated,true);
            $relatedModelName = $relatedController->getModelo();
            $relatedModel = new $relatedModelName();
            $primaryKeyName = $relatedModel->getPrimaryKey();
            $id = $result[$primaryKeyName];
        }
        else
        {
            $id = $data;
            
            //Validación de id
            $relatedModel = new $relatedController->modelo();
            $isModelFound = $relatedModel->find($id);

            //Id a relacionar no encontrado
            if(!$isModelFound)
            {
                $e = new ModelNotFoundException();
                $e->setModel(get_class($relatedModel));
                throw $e;
            }
        }

        $result = $relatedController->generateDBRef($id);
        return $result;
    }

    private function storeEmbsOne($data, $relatedController, $idUserCreated)
    {
        return $relatedController->storeModelFromArray($data, $idUserCreated, false);
    }

    private function storeEmbsMany($dataArray, $relatedController, $idUserCreated)
    {
        $result = [];

        if(!$this->arrayHasStringKeys($dataArray))
        {
            foreach ($dataArray as $data) {
                $result[] = $relatedController->storeModelFromArray($data, $idUserCreated, false);
            }
        }
        return $result;
    }

    private function storeManyToMany($dataArray, $relatedController, $idUserCreated)
    {
        $result = [];

        if(!$this->arrayHasStringKeys($dataArray))
        {
            foreach ($dataArray as $data) {

                if(is_array($data))
                {
                    $relatedObject = $relatedController->storeModelFromArray($data, $idUserCreated, true);

                    $relatedModelName = $relatedController->getModelo();
                    $isModelFound = new $relatedModelName();
                    $primaryKeyName = $isModelFound->getPrimaryKey();

                    $id = $relatedObject[$primaryKeyName];

                }
                else
                {
                    $id = $data;

                    //Validación de id
                    $relatedModel = new $relatedController->modelo();
                    $isModelFound = $relatedModel->find($id);

                    //Id a relacionar no encontrado
                    if(!$isModelFound)
                    {
                        $e = new ModelNotFoundException();
                        $e->setModel(get_class($relatedModel));
                        throw $e;
                    }
                }

                $result[] = $relatedController->generateDBRef($id);
            }
        }

        return $result;
    }

    public function generateDBRef($id)
    {
        $model = new $this->modelo();
        $collection = $model->getTable();
        $objectID = new \MongoDB\BSON\ObjectId($id);

        return ['$ref' => $collection, '$id' => $objectID];
    }

    private function arrayHasStringKeys(array $array) {
        return count(array_filter(array_keys($array), 'is_string')) > 0;
    }
}
