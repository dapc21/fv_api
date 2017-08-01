<?php

/**
 * Describir la clase según PHPDOC
 */

namespace App\Http\Controllers\Resources;

use DB;
use Illuminate\Database\QueryException;
use PDOException;
use Exception;
use App\datatraffic\lib\Util;
use Carbon\Carbon;
use Input;

trait ControllerTraitCustomAttribute
{
  //Nombre de la relación
  private $strRelationName = 'customsAttributes';

  //Lista los atributos de una definición de Recurso
  public function customsattributesList($strIdResourceDefinition)
  {
    $insRequest = Input::instance();
    $arrDataQuery = $insRequest->query();
    $intCode = 200;

    //Obtenemos todos los datos soportados
    $sort      = empty($arrDataQuery['sort'])? null : json_decode($arrDataQuery['sort'], true);
    $filters   = empty($arrDataQuery['filters'])? null : json_decode($arrDataQuery['filters'], true);
    $relations = empty($arrDataQuery['relations'])? null : json_decode($arrDataQuery['relations'], true);
    $page      = empty($arrDataQuery['page'])? 1 : (int)$arrDataQuery['page'];
    $limit     = empty($arrDataQuery['limit'])? 15 : (int)$arrDataQuery['limit'];
    $view      = empty($arrDataQuery['view'])? 'list' : (string)$arrDataQuery['view'];

    //Instanciamos un modelo y constructor de las consultas
    /*$this->insModel = new $this->modelo();
    $this->insQueryBuilder = $this->insModel->query();

    //Filtramos
    if (!empty($filters))
        $this->dFilters($filters);

    //Relacionamos
    if (!empty($relations))
        $this->dRelations($relations);

    //Ordenamos
    if (!empty($sort))
        $this->dOrders($sort);

    //Ejecutamos la consulta configurada
    $result = $this->dPaginate($page, $limit, $view);*/

    $insModel = (new $this->modelo())->find($strIdResourceDefinition);

    $result = (empty($insModel))? [] : $insModel->{$this->strRelationName}()->get()->toArray($view);

    $resultResponse = Util::outputJSONFormat(false, trans('general.MSG_OK'), 0, $result, $view);

    return response($resultResponse, $intCode);
  }

  //Guarda un atriburo de una definición de Recurso (Acumula los atributos)
  public function customsattributesSave($strIdResourceDefinition)
  {
    $insUser = Util::$insUser;
    $insModel = (new $this->modelo())->find($strIdResourceDefinition);
    $insRequest = Input::instance();
    $arrData = $insRequest->json()->all();

    //Obtenemos el id del usuario creador
    $strNameCollection = $insUser->getTable();
    $insDBRefIdUserCreated = $this->dConvetToDBRef($strNameCollection, $insUser->getIdPrimaryKey());
    $idUserCreated = $insDBRefIdUserCreated;

    $arrRelationList = [$arrData];

    //Obtenemos todas las relaciones soportadas
    $arrRelationshipMap = $insModel->getRelationshipMap();

    //Obtenemos la configuración para esa relación
    $arrRelationConfig = $arrRelationshipMap[$this->strRelationName];

    //Agregamos el nombre de la relación a la configuración
    $arrRelationConfig = array_merge($arrRelationConfig, ['name' => $this->strRelationName]);

    //Guardamos la data
    $result = $this->storeObjectEmbOneToMany($insModel, $arrRelationList, $arrRelationConfig, $idUserCreated);

    //Preparando respuesta
    $error = false;
    $msg = trans('general.MSG_OK');
    $data = ["reference" => $result[0]];
    $total = 1;
    $intCode = 201;

    $resultResponse = Util::outputJSONFormat($error, $msg, $total, $data);

    return response($resultResponse, $intCode);
  }

  //Obtiene un atributo específico de una Recurso de dispositivo
  public function customsattributesGet($strIdResourceDefinition, $strIdCustomattribute)
  {
    $intCode = 200;

    $insModel = (new $this->modelo())->find($strIdResourceDefinition);

    $result = $insModel->{$this->strRelationName}()->find($strIdCustomattribute)->toJson();

    return response($result, $intCode);
  }

  //Actualiza un atributo específico de una Recurso de dispositivo
  public function customsattributesUpdate($strIdResourceDefinition, $strIdCustomattribute)
  {
    $insUser = Util::$insUser;
    $insModel = (new $this->modelo())->find($strIdResourceDefinition);
    $insRequest = Input::instance();
    $arrData = $insRequest->json()->all();

    //Obtenemos el id del usuario creador
    $strNameCollection = $insUser->getTable();
    $insDBRefIdUserCreated = $this->dConvetToDBRef($strNameCollection, $insUser->getIdPrimaryKey());
    $idUserCreated = $insDBRefIdUserCreated;

    $arrData['_id'] = $strIdCustomattribute;
    $arrRelationList = [$arrData];

    //Obtenemos todas las relaciones soportadas
    $arrRelationshipMap = $insModel->getRelationshipMap();

    //Obtenemos la configuración para esa relación
    $arrRelationConfig = $arrRelationshipMap[$this->strRelationName];

    //Agregamos el nombre de la relación a la configuración
    $arrRelationConfig = array_merge($arrRelationConfig, ['name' => $this->strRelationName]);

    //Guardamos la data
    $result = $this->updateObjectEmbOneToMany($insModel, $arrRelationList, $arrRelationConfig, $idUserCreated, false);

    //Preparando respuesta
    $error = false;
    $msg = trans('general.MSG_OK');
    $data = ["reference" => $result[0]];
    $total = 1;
    $intCode = 201;

    $resultResponse = Util::outputJSONFormat($error, $msg, $total, $data);

    return response($resultResponse, $intCode);
  }

  //Elimina un atributo específico de una Recurso de dispositivo
  public function customsattributesDelete($strIdResourceDefinition, $strIdCustomattribute)
  {
    //Eliminamos la licencia
    $insModel = (new $this->modelo())->find($strIdResourceDefinition);
    $insModelCustomAttribute = $insModel->{$this->strRelationName}()->find($strIdCustomattribute);
    $insModelCustomAttribute->delete();

    //Preparando respuesta
    $error = false;
    $msg = trans('general.MSG_OK');
    $data = ["reference" => $insModelCustomAttribute->_id];
    $total = 1;
    $intCode = 200;

    //Respondemos
    $resultResponse = Util::outputJSONFormat($error, $msg, $total, $data);
    return response($resultResponse, $intCode);
  }
}
