<?php

namespace App\Http\Controllers\GenericMongo;

/**
 * <b>ControllerTraitIndex:</b> Trait encargado de la gestión del index generico
 *
 * <b>Implementación:</b> Esta clase se usa desde el controlador en la cual se
 * sobreescribir la variable <i>$modelo</i> con la ruta del Modelo correspondiente
 * asi como tambien se debe importar el trait: ejemplo
 * <pre>
 *  class micontroller extends \BaseController{
 *     use \generic\ControllerTraitIndex;
 *     protected $modelo = 'modelo\MiModelo';
 *  }
 * </pre>
 *
 * @copyright 2015 - Datatraffic
 * @author Datatraffic S.A.S. <soporte@datatraffic.com.co>
 */

use App\Models\Tracking\History;
use DB;
use App\datatraffic\lib\Util;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Jenssegers\Mongodb\Relations\BelongsTo;
use Jenssegers\Mongodb\Relations\EmbedsMany;
use Jenssegers\Mongodb\Relations\EmbedsOne;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDatetime;
use PDOException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Input;

use App\Http\Controllers\GenericMongo\GramarController;

Trait ControllerTraitIndex
{

    public function index(Request $request) {

        //DB::connection()->enableQueryLog();

        $sorts = $request->has('sort') ? json_decode(trim($request->get('sort')), true) : [];
        $filters = $request->has('filters') ? json_decode(trim($request->get('filters')), true) : [];
        $relations = $request->has('relations') ? json_decode(trim($request->get('relations'))) : [];
        $view = $request->has('view') ? json_decode(trim($request->get('view'))) : [];

        $page = $request->has('page') ? trim($request->get('page')) : 1;
        $limit = $request->has('limit') ? trim($request->get('limit')) : 15;

        $object = new $this->modelo();
        $query = $object->withoutGlobalScope(new SoftDeletingScope());

        if (!empty($filters)) {
            $query = $this->filters($object, $query, $filters);
        }
        else {
            $usedTraits = class_uses($object);
            $softDeleteTrait = 'Jenssegers\Mongodb\Eloquent\SoftDeletes';

            if (in_array($softDeleteTrait, $usedTraits)) {
                $scope = new SoftDeletingScope();
                $scope->apply($query, $object);
            }
        }

        if (!empty($relations)) {
            $query = $this->relations($object, $query, $relations);
        }

        if (!empty($sorts)) {
            $query = $this->orders($object, $query, $sorts);
        }

        $result = $this->paginate($object, $query, $page, $limit, $view);

        $error = false;
        $msg = trans('general.MSG_OK');
        $data = $result->toArray();
        $total = 1;
        $intCode = 200;

        $result = Util::outputJSONFormat($error, $msg, $total, $data, $view);

        //dump(DB::connection()->getQueryLog());

        return response($result, $intCode);
    }

    public function filters($parentModel, $query, array $filters) {
        $strOpLogic = isset($filters['and'])? 'and' : 'or';
        $query = $this->processFilter($parentModel, $query, $filters[$strOpLogic], $strOpLogic);

        return $query;
    }

    /**
     * @param $parentModel
     * @param $filters
     * @param $strOpLogicParent
     * @return mixed
     */
    private function processFilter($parentModel, $eloquentBuilder, $filters, $strOpLogicParent)
    {
        $onlyTrashed = false;

        $queryBuilder = $eloquentBuilder->getQuery();

        //Procesar filtros
        foreach ($filters as $operator => $filter) {

            //Si es un filtro vacio entonces no hacer nada
            if(empty($filter))
            {
                continue;
            }

            if(isset($filter['and'])) {
                $subquery = $parentModel->query();
                $subquery = $this->processFilter($parentModel, $subquery, $filter['and'], 'and');
                $queryBuilder->addNestedWhereQuery($subquery->getQuery(),$strOpLogicParent);
            }
            else if(isset($filter['or']))
            {
                $subquery = $parentModel->query();
                $subquery = $this->processFilter($parentModel, $subquery, $filter['or'], 'or');
                $queryBuilder->addNestedWhereQuery($subquery->getQuery(),$strOpLogicParent);
            }
            else
            {
                $strComparison = isset($filter['comparison'])?$filter['comparison']:"eq";
                $strField = $filter['field'];
                $strValue = isset($filter['value'])?$filter['value']:null;
                $strSign = GramarController::$operadores[$strComparison];

                //Si es like agregar el % al inicio y al final
                if ($strComparison == "lk") {
                    $strValue = "/" . $strValue . "/i";
                }

                if($this->isRelation($strField))
                {
                    $strValue = $this->processRelationFilter($parentModel, $strField, $strValue);
                }
                else
                {
                    $strValue = $this->finalValue($parentModel, $strField, $strValue);
                }

                switch ($strComparison){
                    case "btw":
                        $queryBuilder->where($strField, '>', $strValue[0],"and");
                        $queryBuilder->where($strField, '<', $strValue[1],"and");
                        break;

                    case "btwe":
                        $queryBuilder->where($strField, '>=', $strValue[0],"and");
                        $queryBuilder->where($strField, '<=', $strValue[1],"and");
                        break;


                    case "isnull":
                        if($strOpLogicParent === 'and') {
                            $queryBuilder->whereNull($strField);
                        }
                        else{
                            $queryBuilder->orWhereNull($strField);
                        }
                        break;

                    case "isnotnull":
                        
                        //Verificar que el campo sea deleted_at para mostrar los eliminados
                        if($strField == 'deleted_at') {
                            $onlyTrashed = true;
                        }

                        if ($strOpLogicParent === 'and') {
                            $queryBuilder->whereNotNull($strField);
                        } else {
                            $queryBuilder->orWhereNotNull($strField);
                        }

                        break;

                    case "in" :
                        if($strOpLogicParent === 'and') {
                            if(!Util::arrayHasStringKeys($strValue)) {
                                $queryBuilder->WhereIn($strField, $strValue);
                            }
                            else{
                                $queryBuilder->WhereIn($strField, [$strValue]);
                            }
                        }
                        else {
                            if(!Util::arrayHasStringKeys($strValue)) {
                                $queryBuilder->orWhereIn($strField, $strValue);
                            }
                            else{
                                $queryBuilder->orWhereIn($strField, [$strValue]);
                            }
                        }
                        break;

                    case "notin" :
                        if($strOpLogicParent === 'and') {
                            if(!Util::arrayHasStringKeys($strValue)) {
                                $queryBuilder->whereNotIn($strField, $strValue);
                            }
                            else{
                                $queryBuilder->whereNotIn($strField, [$strValue]);
                            }
                        }
                        else {
                            if(!Util::arrayHasStringKeys($strValue)) {
                                $queryBuilder->orWhereNotIn($strField, $strValue);
                            }
                            else{
                                $queryBuilder->orWhereNotIn($strField, [$strValue]);
                            }
                        }
                        break;

                    default:
                        $queryBuilder->where($strField, $strSign, $strValue, $strOpLogicParent);
                        break;
                }
            }
        }

        //Asignar el query contruido al builder
        $eloquentBuilder->setQuery($queryBuilder);

        //Aplicar el SoftDeleteScope si es necesario
        if(!$onlyTrashed)
        {
            $usedTraits = class_uses($parentModel);
            $softDeleteTrait = 'Jenssegers\Mongodb\Eloquent\SoftDeletes';

            if (in_array($softDeleteTrait, $usedTraits)) {
                $scope = new SoftDeletingScope();
                $scope->apply($eloquentBuilder, $parentModel);
            }
        }

        //Aplicar el CompanyScope si no tiene permiso para getAllCompanies
        if(!Util::$manageAllCompanies) {
            if (array_key_exists('company', $parentModel->getRelationshipMap())) {
                $scope = new CompanyScope();
                $scope->apply($eloquentBuilder, $parentModel);
            }
        }

        return $eloquentBuilder;
    }

    private function processRelationFilter($parentModel, $strField, $strValue)
    {
        if(preg_match("/^id_/", $strField))
        {
            $strValue =  $this->finalValue($parentModel,$strField, $strValue);
        }
        else
        {
            $arrComponents = explode('.', $strField);

            $strRelationName = $arrComponents[0];
            $relation = $parentModel->$strRelationName();
            $relatedModel = $relation->getRelated();

            if(count($arrComponents) > 2) {
                $strValue =  $this->processRelationFilter($relatedModel, implode('.',array_shift($arrComponents)), $strValue);
                $strField = $strRelationName.'.'.$strField;
            }
            else
            {
                $strFieldName = $arrComponents[1];
                $strValue =  $this->finalValue($relatedModel, $strFieldName, $strValue);
            }
        }

        return $strValue;
    }

    private function finalValue($parentModel, $strFieldName, $strValue)
    {
        if($strValue == null)
        {
            return $strValue;
        }

        $keyWithoutId = preg_replace("/^id_/", "$1", $strFieldName);
        $arrRelationsConfig = $parentModel->getRelationshipMap();
        $dates = $parentModel->getDates();

        if (array_key_exists($keyWithoutId, $arrRelationsConfig)) {
            if ($arrRelationsConfig[$keyWithoutId]['type'] === 'onetoone' || $arrRelationsConfig[$keyWithoutId]['type'] === 'manytomany')
            {
                $insControllerRelation = new $arrRelationsConfig[$keyWithoutId]['foreign_controller'];
                $insModelRelation = new $insControllerRelation->modelo();
                $strCollectionName = $insModelRelation->getTable();

                if (is_array($strValue)) {
                    $arrayStrValue = $strValue;
                    $strValue = [];
                    foreach ($arrayStrValue as $itemValue) {
                        $strValue[] = $this->dConvetToDBRef($strCollectionName, $itemValue);
                    }
                }
                else {
                    $strValue = $this->dConvetToDBRef($strCollectionName, $strValue);
                }
            }
        }
        else if($strFieldName === '_id')
        {
            if (is_array($strValue)) {
                $arrayStrValue = $strValue;
                $strValue = [];
                foreach ($arrayStrValue as $itemValue) {
                    $strValue[] = new ObjectID($itemValue);
                }
            }
            else {
                $strValue = new ObjectID($strValue);
            }
        }
        else if(in_array($strFieldName, $dates))
        {
            if (is_array($strValue)) {
                $arrayStrValue = $strValue;
                $strValue = [];
                foreach ($arrayStrValue as $itemValue) {
                    $strValue[] = new UTCDatetime(Carbon::createFromFormat('Y-m-d H:i:s',$itemValue,'GMT-0')->getTimestamp() * 1000);
                }
            }
            else {
                $strValue = new UTCDatetime(Carbon::createFromFormat('Y-m-d H:i:s',$strValue,'GMT-0')->getTimestamp() * 1000);
            }
        }

        return $strValue;
    }

    private function isRelation($strField)
    {
        return strpos($strField, ".") || preg_match("/^id_/", $strField);
    }

    public function relations($parentModel, $query, $relations)
    {
        //Verificamos que no tenga repetidas
        $arrRelations = array_unique($relations);

        //Configuraciones de las relaciones
        $arrRelationsConfig = $parentModel->getRelationshipMap();

        //Obtenemos las relaciones permitidas para el modelo
        $arrRelationsPermitted = array_intersect($this->insModel->getWhiteWith(), array_keys($arrRelationsConfig));

        //Cargamos las relaciones
        foreach ($arrRelations as $strRelation) {
            if(in_array($strRelation, $arrRelationsPermitted)) {

                $query->with($strRelation);
            }
        }

        return $query;
    }

    public function orders($parentModel, $query, $sorts)
    {
        foreach ($sorts as $strSort){
            if (!empty($strSort["property"])) {

                $strProperty  = $strSort["property"];
                $strDirection = empty($strSort["direction"])? "ASC" : $strSort["direction"];

                $query->orderBy($strProperty, $strDirection);
            }
        }

        return $query;
    }

    public function paginate($object, $query, $page, $limit, $views)
    {
        if($limit > 0) {
            $result = $query->paginate((int)$limit, $views, 'page', $page);
        }
        else {
            $finalView = [];
            foreach ($views as $view)
            {
                $finalView[$view] = 1;
            }
            $result = $query->project($finalView)->get();
        }

        return $result;
    }

    public function show(Request $request, $strId)
    {
        $intCode = 200;

        $filters = ['and' => [['field' => '_id', 'comparison' => 'eq', 'value' => $strId]]];
        $relations = $request->has('relations') ? json_decode($request->get('relations')) : [];
        $view = $request->has('view') ? json_decode($request->get('view')) : [];

        $page = 1;
        $limit = -1;

        $object = new $this->modelo();
        $query = $object->query();

        $query = $this->filters($object, $query, $filters);

        if (!empty($relations)) {
            $query = $this->relations($object, $query, $relations);
        }

        $result = $this->paginate($object, $query, $page, $limit, $view);

        if($result->count() == 0)
        {
            $e = new ModelNotFoundException();
            $e->setModel(get_class($object));
            throw $e;
        }

        $data = $result->pop()->toArray();

        return response($data, $intCode);
    }
}
