<?php

/**
 * Describir la clase según PHPDOC
 */

namespace App\Http\Controllers\Resources;

use App\Models\ResourceDefinition;
use DB;
use Illuminate\Database\QueryException;
use PDOException;
use Exception;
use App\datatraffic\lib\Util;
use Carbon\Carbon;
use Input;

trait ControllerTraitDeviceDefinition
{
  //Nombre de la relación
  private $strRelationDevice = 'devicesDefinitions';

  //Lista las definiciones de dispositivos de una definición de recurso
  public function devicesdefinitionsList($strIdResourceDefinition)
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

    $result = (empty($insModel))? [] : $insModel->{$this->strRelationDevice}()->get()->toArray($view);

    $resultResponse = Util::outputJSONFormat(false, trans('general.MSG_OK'), 0, $result, $view);

    return response($resultResponse, $intCode);
  }

  //Guarda una definicion de dispositivo de una definición de recurso (Acumula los atributos)
  public function devicesdefinitionsSave($strIdResourceDefinition)
  {
    $insUser = Util::$insUser;
    $insModel = (new $this->modelo())->find($strIdResourceDefinition);
    $insRequest = Input::instance();
    $arrData = $insRequest->json()->all();

    //Asociamos el dispositivo
    $insModel->{$this->strRelationDevice}()->attach($arrData['_id']);

    /*
    //Obtenemos el id del usuario creador
    $strNameCollection = $insUser->getTable();
    $insDBRefIdUserCreated = \MongoDBRef::create($strNameCollection, new \MongoId($insUser->getIdPrimaryKey()));
    $idUserCreated = $insDBRefIdUserCreated;

    $arrRelationList = [$arrData];

    //Obtenemos todas las relaciones soportadas
    $arrRelationshipMap = $insModel->getRelationshipMap();

    //Obtenemos la configuración para esa relación
    $arrRelationConfig = $arrRelationshipMap[$this->strRelationDevice];

    //Agregamos el nombre de la relación a la configuración
    $arrRelationConfig = array_merge($arrRelationConfig, ['name' => $this->strRelationDevice]);

    //Guardamos la data
    //$result = $this->storeObjectEmbOneToMany($insModel, $arrRelationList, $arrRelationConfig, $idUserCreated);

    $strControllerName = $arrRelationConfig['foreign_controller'];
    $insControllerRelation = new $strControllerName();
    $strModelName = $insControllerRelation->dGetModelo();
    $insModelRelation = new $strModelName();

    foreach ($arrData as $key => $value)
      $insModelRelation->$key = $value;

    $insModelRelation->save();

    $insModel = (new $this->modelo())->find($strIdResourceDefinition);

    $insModel->{$this->strRelationDevice}()->save($insModelRelation);*/

    //Preparando respuesta
    $error = false;
    $msg = trans('general.MSG_OK');
    $data = ["reference" => $arrData['_id']];
    $total = 1;
    $intCode = 201;

    $resultResponse = Util::outputJSONFormat($error, $msg, $total, $data);

    return response($resultResponse, $intCode);
  }

  //Obtiene una definicion de dispositivo específico de una definición de recurso
  public function devicesdefinitionsGet($strIdResourceDefinition, $strIdDeviceDefinition)
  {

    $intCode = 200;

    $insModel = (new $this->modelo())->find($strIdResourceDefinition);

    $result = $insModel->{$this->strRelationDevice}()->find($strIdDeviceDefinition)->toJson();

    return response($result, $intCode);
  }

  //Actualiza una definicion de dispositivo específico de una definición de recurso
  public function devicesdefinitionsUpdate($strIdResourceDefinition, $strIdDeviceDefinition)
  {
    $insUser = Util::$insUser;
    $insModel = (new $this->modelo())->find($strIdResourceDefinition);
    $insModelRelation = $insModel->{$this->strRelationDevice}()->find($strIdDeviceDefinition);
    $insRequest = Input::instance();
    $arrData = $insRequest->json()->all();

    //Obtenemos el id del usuario creador
    $strNameCollection = $insUser->getTable();
    $insDBRefIdUserCreated = $this->dConvetToDBRef($strNameCollection, $insUser->getIdPrimaryKey());
    $idUserCreated = $insDBRefIdUserCreated;

    //Obtenemos todas las relaciones soportadas
    $arrRelationshipMap = $insModel->getRelationshipMap();

    //Obtenemos la configuración para esa relación
    $arrRelationConfig = $arrRelationshipMap[$this->strRelationDevice];

    foreach ($arrData as $key => $value)
      $insModelRelation->$key = $value;

    $insModelRelation->save();

    //Preparando respuesta
    $error = false;
    $msg = trans('general.MSG_OK');
    $data = ["reference" => $result[0]];
    $total = 1;
    $intCode = 201;

    $resultResponse = Util::outputJSONFormat($error, $msg, $total, $data);

    return response($resultResponse, $intCode);
  }

  //Elimina una definicionde dispositivo específico de una definición de recurso
  public function devicesdefinitionsDelete($strIdResourceDefinition, $strIdDeviceDefinition)
  {
    $insObjectID = new \MongoDB\BSON\ObjectId($strIdDeviceDefinition);
    ResourceDefinition::where('_id','=',$strIdResourceDefinition)->pull('devicesDefinitions',['$ref' => 'devicesDefinitions', '$id' => $insObjectID]);

    //Preparando respuesta
    $error = false;
    $msg = trans('general.MSG_OK');
    $data = ["reference" => $strIdDeviceDefinition];
    $total = 1;
    $intCode = 200;

    //Respondemos
    $resultResponse = Util::outputJSONFormat($error, $msg, $total, $data);
    return response($resultResponse, $intCode);
  }
}
