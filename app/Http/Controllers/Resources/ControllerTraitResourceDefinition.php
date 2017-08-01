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

trait ControllerTraitResourceDefinition
{
  //Nombre de la relación
  private $strRelationResources = 'resourcesDefinitions';

  //Lista las definicines de recursos de una definición de recurso
  public function resourcesdefinitionsList($strIdResourceDefinition)
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

    $result = (empty($insModel))? [] : $insModel->{$this->strRelationResources}()->get()->toArray($view);

    $resultResponse = Util::outputJSONFormat(false, trans('general.MSG_OK'), 0, $result, $view);

    return response($resultResponse, $intCode);
  }

  //Guarda una definicion de recurso de una definición de recurso (Acumula los atributos)
  public function resourcesdefinitionsSave($strIdResourceDefinition)
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
    $arrRelationConfig = $arrRelationshipMap[$this->strRelationResources];

    //Agregamos el nombre de la relación a la configuración
    $arrRelationConfig = array_merge($arrRelationConfig, ['name' => $this->strRelationResources]);

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

  //Obtiene una definicion de recurso específico de una definición de recurso
  public function resourcesdefinitionsGet($strIdResourceDefinition, $strIdResource)
  {
    $intCode = 200;

    $insModel = (new $this->modelo())->find($strIdResourceDefinition);

    $result = $insModel->{$this->strRelationResources}()->find($strIdResource)->toJson();

    return response($result, $intCode);
  }

  //Actualiza una definicion de recurso específico de una definición de recurso
  public function resourcesdefinitionsUpdate($strIdResourceDefinition, $strIdResource)
  {
    $insUser = Util::$insUser;
    $insModel = (new $this->modelo())->find($strIdResourceDefinition);
    $insRequest = Input::instance();
    $arrData = $insRequest->json()->all();

    //Obtenemos el id del usuario creador
    $strNameCollection = $insUser->getTable();
    $insDBRefIdUserCreated = $this->dConvetToDBRef($strNameCollection, $insUser->getIdPrimaryKey());
    $idUserCreated = $insDBRefIdUserCreated;

    $insModelResource = $insModel->{$this->strRelationResources}()->find($strIdResource);

    foreach ($arrData as $key => $value) {
      $insModelResource->$key = $value;
    }

    $insModelResource->id_user_update = $idUserCreated;

    $insModelResource->save();

    //Preparando respuesta
    $error = false;
    $msg = trans('general.MSG_OK');
    $data = ["reference" => $strIdResource];
    $total = 1;
    $intCode = 201;

    $resultResponse = Util::outputJSONFormat($error, $msg, $total, $data);

    return response($resultResponse, $intCode);
  }

  //Elimina una definicionde recurso específico de una definición de recurso
  public function resourcesdefinitionsDelete($strIdResourceDefinition, $strIdResource)
  {
    //Eliminamos la licencia
    $insModel = (new $this->modelo())->find($strIdResourceDefinition);
    $insModelResource = $insModel->{$this->strRelationResources}()->find($strIdResource);

    //Ojo no elimina(objeto embebido)
    $insModelResource->delete();
//dd($insModelResource);
    //Preparando respuesta
    $error = false;
    $msg = trans('general.MSG_OK');
    $data = ["reference" => $insModelResource->_id];
    $total = 1;
    $intCode = 200;

    //Respondemos
    $resultResponse = Util::outputJSONFormat($error, $msg, $total, $data);
    return response($resultResponse, $intCode);
  }
}
