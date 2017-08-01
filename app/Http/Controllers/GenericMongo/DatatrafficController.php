<?php

namespace App\Http\Controllers\GenericMongo;

use App\Http\Controllers\Controller;
use App\datatraffic\lib\Util;
use Illuminate\Database\Eloquent\Model;

/**
 * A summary informing the user what the associated element does.
 *
 * A *description*, that can span multiple lines, to go _in-depth_ into the details of this element
 * and to provide some background information or textual references.
 *
 * @version
 * @author
 * @license
 * @copyright
 */
class DatatrafficController extends Controller {

    private $strIdUserCreate = 'id_user_create';
    private $strIdUserUpdate = 'id_user_update';

    use \App\Http\Controllers\GenericMongo\ControllerTraitIndex;
    use \App\Http\Controllers\GenericMongo\ControllerTraitStore;
    use \App\Http\Controllers\GenericMongo\ControllerTraitUpdate;
    use \App\Http\Controllers\GenericMongo\ControllerTraitDestroy;
    use \App\Http\Controllers\GenericMongo\ControllerTraitExcel;

    /**
     * DatatrafficController constructor.
     */
    public function __construct()
    {
        $this->insModel = new $this->modelo();
        $this->insQueryBuilder = $this->insModel->query();
    }

    ////////////////////// De puede colocar en un trait Utils ////////////////////

    /**
     * Obtiene los datos de las relaciones y elimina dichos datos del arreglo de la solicitud inicial
     */
    public function getArrRelationsData(&$insModel, &$arrData)
    {
        $arrRelationsData = [];
        $arrRelationsConfig = $insModel->getRelationshipMap();

        foreach ($arrRelationsConfig as $strNameRelation => $arrConfigRelation) {

            //Esto cambia el id_xxx a xxx en una relación OneToOne para que la pueda crear
            if($arrConfigRelation['type'] == 'onetoone' && !empty($arrData['id_'.$strNameRelation])){
                $arrData[$strNameRelation] = $arrData['id_'.$strNameRelation];
                unset($arrData['id_'.$strNameRelation]);
            }

            //Guardamos las relaciones
            if(!empty($arrData[$strNameRelation])){
                $arrRelationsData = array_merge($arrRelationsData, [ $strNameRelation => $arrData[$strNameRelation] ]);
                unset($arrData[$strNameRelation]);
            }
        }
        return $arrRelationsData;
    }

    /**
    * Obtenemos el usuario autenticado
    */
    public function dGetUserDBRefSession()
    {
      $insUser = Util::$insUser;

      $strNameCollection = $insUser->getTable();
      $insObjectID = new \MongoDB\BSON\ObjectId($insUser->getIdPrimaryKey());

      return ['$ref' => $strNameCollection, '$id' => $insObjectID];
    }

    /**
    * Convierte a BDRef
    */
    public function dConvetToDBRef($strCollection, $strId)
    {
      $insObjectID = new \MongoDB\BSON\ObjectId($strId);

      return ['$ref' => $strCollection, '$id' => $insObjectID];
    }

    /**
    * Verifica si es un DBRef
    */
    public function bIsDBRef($arrDBRef)
    {
      return (array_key_exists('$ref', $arrDBRef) &&
              array_key_exists('$id', $arrDBRef) &&
              is_a($arrDBRef['$id'], '\MongoDB\BSON\ObjectID'));
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

    protected function getCustomAttributes($dataArray)
    {
        return [];
    }
    
    /**
     * return string Retorna el modelo relacionado al modelo
     */
    public function getModelo(){
    	return $this->modelo;
    }
    /**
     * funcion que se ejecuta antes de guardar un modelo
     * se sobrescribe para validar la data antes de guardar
     * @param array $data 
     */
    public function beforeStore( array &$data){
    	
    }
    
    /**
     * funcion que se ejecuta despues de guardar un modelo
     */
    public function afterStore( $id ){
    	 
    }
    
    /**
     * funcion que se ejecuta antes de actualizar un modelo
     se sobrescribe para validar la data antes de guardar
     * @param \Jenssegers\Mongodb\Eloquent\Model $model
     * @param array $arrData
     * @param array $arrRelationsSynchronize
     */
     
    public function beforeUpdate( \Jenssegers\Mongodb\Eloquent\Model $model, array &$arrData, array $arrRelationsSynchronize){
    	 
    }
    
    /**
     * funcion que se ejecuta despues de actualizar un modelo
     */
    public function afterUpdate( $id ){
    
    }
    
    /**
     * Se usa para hacer operaciones de validacion y verificar si se puede eliminar el modelo
     * @param unknown $id
     */
    public function beforeDeleted( $id ){
    
    }
    /**
     * Se usa para hacer las operaciones pertinentes despues de eliminar un modelo
     * @param \Jenssegers\Mongodb\Eloquent\Model $model
     */
    public function afterDeleted( \Jenssegers\Mongodb\Eloquent\Model $model ){
    
    }      
    
}
