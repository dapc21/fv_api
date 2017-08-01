<?php

/**
 * Describir la clase según PHPDOC
 */

namespace App\Http\Controllers\Devices;

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

  //Lista las licencias de una compañía
  public function customsattributesList($strIdDeviceDefinition)
  {
    return 'customsattributesList';
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

    $insModel = (new $this->modelo())->find($strIdCompany);

    $result = $insModel->{$this->strRelationName}()->paginate($limit)->toArray($view);

    $resultResponse = Util::outputJSONFormat(false, trans('general.MSG_OK'), 0, $result, $view);

    return response($resultResponse, $intCode);
  }

  //Guarda la licencia de una compañía (Acumula las licencias)
  public function customsattributesSave($strIdDeviceDefinition)
  {
    return 'customsattributesSave';
    $insUser = Util::$insUser;
    $insModel = (new $this->modelo())->find($strIdCompany);
    $insRequest = Input::instance();
    $arrData = $insRequest->json()->all();

    //Obtenemos el id del usuario creador
    $strNameCollection = $insUser->getTable();
    $insDBRefIdUserCreated = \MongoDBRef::create($strNameCollection, new \MongoId($insUser->getIdPrimaryKey()));
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

  //Obtiene una licencia específica de una compañía
  public function customsattributesGet($strIdDeviceDefinition, $strIdCustomattribute)
  {
    return 'customsattributesGet';
    $intCode = 200;

    $insModel = (new $this->modelo())->find($strIdCompany);

    $result = $insModel->{$this->strRelationName}()->find($strIdLicense)->toJson();

    return response($result, $intCode);
  }

  //Actualiza una licencia específica de una compañía
  public function customsattributesUpdate($strIdDeviceDefinition, $strIdCustomattribute)
  {
    return 'customsattributesUpdate';
    $insUser = Util::$insUser;
    $insModel = (new $this->modelo())->find($strIdCompany);
    $insRequest = Input::instance();
    $arrData = $insRequest->json()->all();

    //Obtenemos el id del usuario creador
    $strNameCollection = $insUser->getTable();
    $insDBRefIdUserCreated = \MongoDBRef::create($strNameCollection, new \MongoId($insUser->getIdPrimaryKey()));
    $idUserCreated = $insDBRefIdUserCreated;

    $arrData['_id'] = $strIdLicense;

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

  //Elimina una licencia específica de una compañía
  public function customsattributesDelete($strIdDeviceDefinition, $strIdCustomattribute)
  {
    return 'customsattributesDelete';
    //Eliminamos la licencia
    $insModel = (new $this->modelo())->find($strIdCompany);
    $insModelLicense = $insModel->{$this->strRelationName}()->find($strIdLicense);
    $insModelLicense->delete();

    //Preparando respuesta
    $error = false;
    $msg = trans('general.MSG_OK');
    $data = ["reference" => $insModelLicense->_id];
    $total = 1;
    $intCode = 200;

    //Respondemos
    $resultResponse = Util::outputJSONFormat($error, $msg, $total, $data);
    return response($resultResponse, $intCode);
  }
}
