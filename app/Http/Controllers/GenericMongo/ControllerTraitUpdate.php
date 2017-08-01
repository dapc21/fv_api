<?php

/**
 * Describir la clase según PHPDOC
 */

namespace App\Http\Controllers\GenericMongo;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use DB;
use Exception;
use App\datatraffic\lib\Util;
use Validator;

trait ControllerTraitUpdate
{
    /**
     * Función principal encargada de actualizar los datos enviados
     */
    public function update(Request $request, $strId)
    {
        //DB::connection()->enableQueryLog();
        $error = true;
        $msg = trans('general.GENERAL_ERROR');
        $total = 0;
        $data = [];
        $view = null;
        $intCode = 200;

        $idUserCreated = $this->dGetUserDBRefSession();

        if($request->isJson()){
			try{
				$json = $request->all();
			}
			catch(\Exception $e){
				$json = $request->getContent();
			}
        }
        else {
            $json = $request->getContent();
        }

		if(!is_array($json)) {
			$arrData = json_decode($json, true);
		}
		else {
			$arrData = $json;
		}
				
        //Arreglo de sincronizacion
        $arrRelationsSynchronize = [];
        if(isset($arrData['synchronize']))
        {
            $arrRelationsSynchronize = $arrData['synchronize'];
            unset($arrData['synchronize']);
        }

        //Encontrar modelo
        $insModel = new $this->modelo();
        $isModelFound = $insModel->find($strId);

        //Id a relacionar no encontrado
        if(!$isModelFound)
        {
            $e = new ModelNotFoundException();
            $e->setModel(get_class($insModel));
            throw $e;
        }

        $insModel = $isModelFound;
		//rutina antes de actualizar
        $this->beforeUpdate($insModel,$arrData, $arrRelationsSynchronize);
        //actualiza
        $this->updateObject($insModel, $arrData, $arrRelationsSynchronize, $idUserCreated);
        //rutina despues de actualizar
        $this->afterUpdate($insModel->getIdPrimaryKey());

        $error = false;
        $msg = trans('general.MSG_OK');
        $data = ["reference" => $insModel->getIdPrimaryKey()];
        $total = 1;

        
        $result = Util::outputJSONFormat($error, $msg, $total, $data, $view);

        //dump(DB::connection()->getQueryLog());


        return response($result, $intCode);
    }

    /** Actualiza los atributos de un objecto y sus relaciones
     * @param $parentModel Instancia del modelo
     * @param $arrData Informacion unicamente del objeto padre.
     * @param $arrRelationsSincronize Nombre de las relaciones que se deben sincronizar
     * @param $idUserUpdated Id del usuario que esta actualizando
     * @return mixed
     */
    public function updateObject(&$parentModel, $arrData, $arrRelationsSincronize, $idUserUpdated)
    {
        $this->updateModelAttributes($parentModel, $arrData, $idUserUpdated);
        $this->updateModelRelations($parentModel, $arrData, $arrRelationsSincronize, $idUserUpdated);

        return $parentModel;
    }

    /** Actualiza unicamente los atributos de un objecto
     * @param $arrData Informacion del objecto a actualizar.
     * @param $parentModel Instacia del objecto.
     * @param null $idUserUpdated Id del usuario que esta actualizando.
     * @return mixed
     */
    private function updateModelAttributes(&$parentModel, $arrData, $idUserUpdated)
    {
        $id_company = null;
        if(array_key_exists('id_company',$arrData)) {
            $id_company = $arrData['id_company'];
        }

        //Obtenemos reglas de validacion
        $validationRulesForUpdate = [];
        $validationRules = $parentModel->getValidationRules('UPDATE', $id_company);
        if(!empty($validationRules)) {
            $validationRulesForUpdate = array_intersect_key ( $validationRules,  $arrData);
        }
        //Validar
        if(!empty($validationRulesForUpdate)) {
            $validator = Validator::make($arrData, $validationRulesForUpdate);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
        }

        //Obtenemos el mapeo de las relaciones para ese modelo
        $arrRelationshipMap = $parentModel->getRelationshipMap();

        //Obtenemos el arreglo de atributos los que esten en la variable $guarded
        //porque estos no se pueden actualizar
        $guarded = $parentModel->getGuarded();

        //Actualizamos atributos
        foreach ($arrData as $strKey => $data){

            //Quitamos id_ porque es reservado para las relaciones OneToOne
            //id_parent es caso especial (preguntas de formulario) donde no se quita.
            if($strKey != 'id_parent') {
                $strKey = preg_replace("/^id_/", "$1", $strKey);
            }

            //Asignar unicamente si la llave no esta en $guarded
            if(!array_key_exists($strKey, $arrRelationshipMap)  && !in_array($strKey, $guarded)) {
                $parentModel->$strKey = $data;
            }
        }

        //Agregamos usuario de modificacion
        if (!is_null($idUserUpdated)) {
            $strIdUserCreate = $this->strIdUserUpdate;
            $parentModel->$strIdUserCreate = $idUserUpdated;
        }

        $parentModel->save();

        return $parentModel;
    }

    /** Actualiza las relaciones de un objecto
     * @param $arrData Informacion de las relaciones
     * @param $parentModel Instancia del objecto.
     * @param $arrRelationsSincronize Arreglo con los nombres de las relaciones que se deben sincronizar
     * @param $idUserUpdated Id del usuario que esta actualizando.
     * @return mixed
     */
    private function updateModelRelations(&$parentModel, $arrData, $arrRelationsSincronize, $idUserUpdated)
    {
        //Obtenemos el mapeo de las relaciones para ese modelo
        $arrRelationshipMap = $parentModel->getRelationshipMap();

        //Obtenemos el arreglo de atributos los que esten en la variable $guarded
        //porque estos no se pueden actualizar
        $guarded = $parentModel->getGuarded();

        //Guardamos los modelos relacionados
        foreach($arrData as $strRelationName => $arrRelationData){

            //Quitamos id_ porque es reservado para las relaciones OneToOne
            $strRelationName = preg_replace("/^id_/", "$1", $strRelationName);

            //Verificamos exista condiguración para la relación y que no este en $guarded
            if( array_key_exists($strRelationName, $arrRelationshipMap) &&  !in_array($strRelationName, $guarded)){

                //Obtenemos la configuración para esa relación
                $arrRelationConfig = $arrRelationshipMap[$strRelationName];
                $strRelationType = $arrRelationConfig['type'];

                //Agregamos el nombre de la relación a la configuración
                $arrRelationConfig = array_merge($arrRelationConfig, ['name' => $strRelationName]);

                //Tipo de sincronización
                $bSincronize = in_array($strRelationName, $arrRelationsSincronize);

                switch ($strRelationType) {
                    case'onetoone':
                        $relatedModel = $this->updateObjectOneToOne($parentModel, $arrRelationData, $arrRelationConfig, $idUserUpdated);
                        $strIdForeign = $arrRelationConfig['pivot_id_foreign'];
                        $parentModel->$strIdForeign = $relatedModel->getDBRef();
                        $parentModel->save();
                        break;
                    case 'manytomany':
                        $this->updateObjectManyToMany($parentModel, $arrRelationData, $arrRelationConfig, $idUserUpdated, $bSincronize);
                        break;
                    case 'embedsone':
                        $this->updateObjectEmbOneToOne($parentModel, $arrRelationData, $arrRelationConfig, $idUserUpdated);
                        break;
                    case'embedsmany':
                        $this->updateObjectEmbOneToMany($parentModel, $arrRelationData, $arrRelationConfig, $idUserUpdated, $bSincronize);
                        break;
                    default :
                        break;
                }
            }
        }

        return $parentModel;
    }

    /** Actualiza el DBRef con $arrRelationData de la relacion OneToOne configurada en $arrRelationConfig en el objecto padre $insParentModel
     * @param $parentModel Objeto padre
     * @param $arrRelationData Arreglo de informacion de la relacion
     * @param $arrRelationConfig Configuracion de la relacion
     * @param $idUserUpdated Id del usuario que actualiza
     * @return mixed
     * @throws MissingObjectException
     */
    private function updateObjectOneToOne(&$parentModel, $arrRelationData, $arrRelationConfig, $idUserUpdated)
    {
        //Instancia del controlador relacionado
        $relatedController = new $arrRelationConfig['foreign_controller'];

        //Cuando se crea ó relaciona el ID
        if(is_array($arrRelationData))
        {
            //Se inserta el modelo relacionado
            $relatedModel = $relatedController->storeModelFromArray($arrRelationData, $idUserUpdated, true);
            $relatedId = $relatedModel['_id'];
        }
        else
        {
            $relatedId = $arrRelationData;
        }

        //Validación de id
        $relatedModel = new $relatedController->modelo();
        $isModelFound = $relatedModel->find($relatedId);

        //Id a relacionar no encontrado
        if(!$isModelFound)
        {
            $e = new ModelNotFoundException();
            $e->setModel(get_class($relatedModel));
            throw $e;
        }
        $relatedModel = $isModelFound;

        return $relatedModel;
    }

    /**
     * @param $parentModel Objeto padre
     * @param $arrRelationData Arreglo de informacion de la relacion
     * @param $arrRelationConfig Configuracion de la relacion
     * @param $idUserUpdated Id del usuario que actualiza
     * @param $bSincronize True para indicar que no se acumula
     * @return array
     * @throws MissingObjectException
     */
    private function updateObjectManyToMany(&$parentModel, $arrRelationData, $arrRelationConfig, $idUserUpdated, $bSincronize)
    {
        $idRelatedModels = [];
        $strRelationName = $arrRelationConfig['name'];

        //Eliminamos todas las referencias
        if($bSincronize)
        {
            $parentModel->$strRelationName()->detach();
        }

        foreach($arrRelationData as $data){

            //Insertamos/Obtenemos (Lo podemos tratar cómo un objeto embebido OneToOne)
            $relatedModel = $this->updateObjectOneToOne($parentModel, $data, $arrRelationConfig, $idUserUpdated);

            //Relacionamos los ids con el padre (Se puede utilizar attach($Id))
            $parentModel->$strRelationName()->save($relatedModel);

            //Agregamos el id del modelo guardado
            $idRelatedModels[] = $relatedModel->getIdPrimaryKey();
        }

        return $idRelatedModels;
    }

    /**
     * @param $parentModel
     * @param $arrRelationList
     * @param $arrRelationConfig
     * @param $idUserUpdated
     * @return mixed
     * @throws Exception
     */
    private function updateObjectEmbOneToOne(&$parentModel, $arrRelationData, $arrRelationConfig, $idUserUpdated)
    {
        //Nombre de la relación
        $strRelationName = $arrRelationConfig['name'];

        //Instanciamos el controlador
        $relatedController = new $arrRelationConfig['foreign_controller'];

        //Obtenemos/Instanciamos el modelo
        $relatedModel = null;
        if(!empty($arrRelationData['_id'])) {
            $relatedModel = $parentModel->$strRelationName()->find($arrRelationData['_id']);
        }
        if(is_null($relatedModel))
        {
          $relatedModel = new $relatedController->modelo;
        }

        //Se guarda el elemento (se vincula con la clase padre)
        $parentModel->$strRelationName()->save($relatedModel);

        //Hacemos el actualizado genérico
        $relatedModel = $relatedController->updateObject($relatedModel, $arrRelationData, [], $idUserUpdated);

        return $relatedModel;
    }

    /**
     * @param $parentModel
     * @param $arrRelationData
     * @param $arrRelationConfig
     * @param $idUserCreated
     * @param $bSincronize
     * @return array
     * @throws MissingObjectException
     */
    private function updateObjectEmbOneToMany(&$parentModel, $arrRelationData, $arrRelationConfig, $idUserCreated, $bSincronize)
     {
         //Ids de los elementos modificados
         $idRelatedModels = [];

         foreach($arrRelationData as $data){

             //Actualizamos (Lo podemos tratar cómo un objeto embebido OneToOne)
             $relatedModel = $this->updateObjectEmbOneToOne($parentModel, $data, $arrRelationConfig, $idUserCreated);

             //Agregamos el id del modelo guardado
             $idRelatedModels[] = $relatedModel->getIdPrimaryKey();
         }

         //Eliminamos todos los documentos que no se afectaron, si nos lo indica el sincronize
         if($bSincronize)
         {
            //Nombre de la relación
            $strRelationName = $arrRelationConfig['name'];
            //Obtenemos todos los documentos
            $listAllDataModel = $parentModel->$strRelationName;

            //Eliminamos los ids que no se usaron
            foreach($listAllDataModel as $insDataModel)
            {
                if (!in_array($insDataModel->getIdPrimaryKey(), $idRelatedModels))
                {
                    $insDataModel->forceDelete();
                }
            }
         }

         return $idRelatedModels;
     }
     
}
