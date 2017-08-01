<?php

namespace App\Http\Controllers\GenericMongo;

use Carbon\Carbon;
use \Illuminate\Support\Facades\Input;
use App\datatraffic\lib\Util;
use \Illuminate\Database\QueryException;
use Exception;
use DB;

Trait ControllerTraitDestroy
{
    /**
     * Función principal encargada de eliminar los datos enviados
     */
    public function destroy($strId)
    {
        //try{
        $error = true;
        $msg = trans('general.GENERAL_ERROR');
        $total = 0;
        $data = null;
        $view = null;
        $idUserCreated = '1';
        $insUser = Util::$insUser;
        $intCode = 200;

        if(!empty($insUser)){
            //Obtenemos el nombre de la clave primaria
            $strPrimaryKeyName = $insUser->getPrimaryKey();

            //Verificamos que tenga la clave primaria
            if(!empty($insUser->$strPrimaryKeyName)){

                //Obtenemos el id del usuario creador(MongoDBRef)
                $idUserCreated = $this->dGetUserDBRefSession();

                //Verificamos que data eliminar
                $insRequest = Input::instance();
                $arrData = $insRequest->json()->all();
				//dispara el trigger de validacion
                $this->beforeDeleted($strId);
                //Eliminamos la data
                $insModelDelete = $this->deleteGeneric($strId, $idUserCreated, $arrData);
                
                if(!empty($insModelDelete)){
                	//dispara el trigger despues de eliminar
                	$this->afterDeleted( $insModelDelete );
                    //Preparamos los datos
                    $error = false;
                    $msg = trans('general.MSG_OK');
                    $data = ["reference" => $insModelDelete->getIdPrimaryKey()];
                    $total = 1;
                }else{
                    $msg = trans('general.MISSING_DATA_PARAMETER');
                    $intCode = 404;
                }
            }else{
                $msg = trans('general.MISSING_ID_USER_UPDATE');
                $intCode = 422;
            }
        }else{
            $msg = trans('general.MISSING_ID_USER_UPDATE');
            $intCode = 422;
        }


        /*} catch (ExceptionValidation $ex) {
            DB::rollback();
            $rs = ["error" => true, "msg" => $ex->getMessagesError(), "data" => []];
            return $rs;
        } catch (\PDOException $ex) {
            DB::rollback();
            $rs = ["error" => true, "msg" => $ex->getMessage(), "data" => []];
            return $rs;
        } catch (QueryException $ex) {
            DB::rollback();
            $rs = ["error" => true, "msg" => "Error interno ", "data" => []];
            return $rs;
        } catch (\Exception $ex) {
            DB::rollback();
            $rs = ["error" => true, "msg" => $ex->getMessage(), "data" => []];
            return $rs;
        }*/
        $result = Util::outputJSONFormat($error, $msg, $total, $data, $view);
        return response($result, $intCode);
    }

    /**
     * Función que elimina genérico
     */
    public function deleteGeneric($strId, $idUserCreated = 0, &$arrData = null, &$insController = null, &$insModel = null)
    {
        if(is_null($insController))
            $insController = $this;

        if(is_null($insModel)){
           $insModel = new $this->modelo();
           $insModel = $insModel->find($strId);
        }

        if(!is_null($arrData) && !is_null($insModel)){

            //Obtenemos la información de las relaciones
            $arrRelationsData = $insController->getArrRelationsData($insModel, $arrData);

            //Delete los datos
            $insModel = $insController->deleteObject($insModel, $arrData, $arrRelationsData, $idUserCreated);

            return $insModel;
        }

        return null;
    }

    /**
     * Función que elimina todos los datos
     */
    public function deleteObject(&$insModel, &$arrData, &$arrRelations, $idUserCreated)
    {
        try{
            //Eliminamos el modelo base si no tenemos relaciones
            if(empty($arrRelations)){
              $insModel = $this->deleteModel($arrData, $insModel, $idUserCreated);
            }else{
              //Obtenemos el mapeo de las relaciones para ese modelo
              $arrRelationshipMap = $insModel->getRelationshipMap();

              //Eliminamos los modelos relacionados
              foreach($arrRelations as $strRelationName => $arrRelationList){

                  //Verificamos exista condiguración para la relación
                  if( array_key_exists($strRelationName, $arrRelationshipMap) ){

                      //Obtenemos la configuración para esa relación
                      $arrRelationConfig = $arrRelationshipMap[$strRelationName];
                      $strRelationType = $arrRelationConfig['type'];

                      //Agregamos el nombre de la relación a la configuración
                      $arrRelationConfig = array_merge($arrRelationConfig, ['name' => $strRelationName]);

                      switch ($strRelationType) {
                          case'onetoone':
                              //Elimino la referencia
                              $insModel->$strRelationName()->dissociate();
                              $insModel->save();

                              /* Opción Dos
                              $strIdForeign = $arrRelationConfig['pivot_id_foreign'];
                              unset($insModel->$strIdForeign);
                              $insModel->save();
                              */
                              break;
                          case 'manytomany':
                              $arrIdsModelRelation = $this->deleteObjectManyToMany($insModel, $arrRelationList, $arrRelationConfig, $idUserCreated);
                              break;
                          case 'embedsone':
                              $this->deleteObjectEmbOneToOne($insModel, $arrRelationList, $arrRelationConfig, $idUserCreated);
                              break;
                          case'embedsmany':
                              $this->deleteObjectEmbOneToMany($insModel, $arrRelationList, $arrRelationConfig, $idUserCreated);
                              break;
                          /*case'onetomany':
                              $this->storeObjectOneToMany($parentModel, $relation, $idUserCreated);
                              break;
                          case 'embmanytomany':
                              $this->updateObjectEmbPivotManyToMany($insModel, $arrRelationList, $arrRelationConfig, $idUserCreated, $bSincronize);
                              break;*/
                          default :
                              break;
                      }
                  }
              }
            }



            return $insModel;

        } catch (\Exception $ex){
          // DB::rollback();
            //$dbTransaccion->rollback();
           throw $ex;
        }
    }

    /**
     * Eliminamos el modelo y retorna dicho modelo eliminado
     */
    public function deleteModel($arrData, &$insModel, $idUserCreated = null)
    {
        if (!is_null($idUserCreated)) {
            $strIdUserCreate = $this->strIdUserUpdate;
            $insModel->$strIdUserCreate = $idUserCreated;
            $insModel->save();
        }

        $insModel->delete();

        return $insModel;
    }

    /**
     *  Elimina un modelo de tipo uno a uno y lo relaciona con el modelo base
     */
    public function deleteObjectOneToOne(&$insParentModel, &$arrRelationList, &$arrRelationConfig, $idUserCreated)
    {
        $insRelationModel = null;

        if(count($arrRelationList) == 1){
            //Instancia del controlador relacionado
            $insRelationController = new $arrRelationConfig['foreign_controller'];
            $strIdRelation = '0';

            //Cuando se crea ó relaciona el ID
            if(is_array($arrRelationList)){
                //Información relacionada
                $arrData = $arrRelationList;

                //Se elimina el modelo relacionado
                $insRelationModel = $insRelationController->deleteGeneric($idUserCreated, $arrData, $insRelationController, $insRelationModel);

            }else{
                //Id a relacionar
                $strIdRelation = $arrRelationList;

                //Validación de id
                $insRelationModel = new $insRelationController->modelo();

                //Id a relacionar no encontrado
                if(empty($insRelationModel = $insRelationModel->find($strIdRelation))){
                    throw new Exception(trans('general.MISSING_DATA_ID'));
                }
            }
        }else{
           throw new Exception(trans('general.BAD_JSON_FORMAT'));
        }

        return $insRelationModel;
    }

    /**
     * Elimina las referencias (relación muchos a muchos)
     */
    public function deleteObjectManyToMany(&$insParentModel, &$arrRelationList, &$arrRelationConfig, $idUserCreated)
    {
        $strNameRelation = $arrRelationConfig['name'];

        //Desvinculamos todos los ids
        $insParentModel->$strNameRelation()->detach($arrRelationList);

        return $arrRelationList;
    }

    /**
     * Elimina un objeto embebido en el modelo padre relación uno a uno
     */
    public function deleteObjectEmbOneToOne(&$insParentModel, &$arrRelationList, &$arrRelationConfig, $idUserCreated)
    {
        $insRelationModel = null;

        //Nombre de la relación
        $strRelationName = $arrRelationConfig['name'];

        //Instanciamos el controlador
        $insRelationController = new $arrRelationConfig['foreign_controller'];

        //Sólo es un elemento
        $arrRelationData = $arrRelationList;

        //Obtenemos/Instanciamos el modelo
        if(!empty($arrRelationData['_id'])){
          //Buscamos el modelo
          $insRelationModel = $insParentModel->$strRelationName()->find($arrRelationData['_id']);

          //En caso que sea un objeto embebido, se obtiene de otra forma
          if(is_null($insRelationModel)){

            $insRelationModel = $insParentModel->$strRelationName;

            //Verificamos que tengamos el modelo correcto por el ID
            if(is_null($insRelationModel) || $insRelationModel->_id != $arrRelationData['_id'])
              throw new Exception("No se encontró el elemento a modificar", 1);
          }
        }

        //Hacemos el eliminado genérico
        if(!is_null($insRelationModel))
          $insRelationModel = $insRelationController->deleteGeneric('sin-id', $idUserCreated, $arrRelationData, $insRelationController, $insRelationModel);

        return $insRelationModel;
    }

    /**
     * Elimina una lista de elementos embebidos en el modelo padre relación uno a muchos
     */
     public function deleteObjectEmbOneToMany(&$insParentModel, &$arrRelationList, &$arrRelationConfig, $idUserCreated)
     {
         //Ids de los elementos modificados
         $idRelatedModels = [];

         foreach($arrRelationList as $arrRelationData){

             //Actualizamos (Lo podemos tratar cómo un objeto embebido OneToOne)
             $insModel = $this->deleteObjectEmbOneToOne($insParentModel, $arrRelationData, $arrRelationConfig, $idUserCreated);

             //Agregamos el id del modelo guardado
             $idRelatedModels[] = $insModel->getIdPrimaryKey();
         }

         return $idRelatedModels;
     }
}
