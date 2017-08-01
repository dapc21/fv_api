<?php

namespace App\datatraffic\lib;

use App\Models\Forms\Register;
use App\Models\Planning\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use Log;
use MongoDB\BSON\ObjectID;
use URL;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;

class Util
{
    
    public static $insUser = null;
    public static $manageAllCompanies = false;
    public static $manageAllResource = false;
    public static $manageAllCreated = false;
    //permite manejar todos los roles
    public static $manageSystemRoles = false;
    public static $viewErrors = false;

    public function __construct() {
        
    }

    /** se borro arrayValidateList
     * 
     * @param unknown $error
     * @param unknown $msg
     * @param unknown $total
     * @param unknown $data
     * @return Ambigous <multitype:, multitype:unknown NULL >
     */
    public static function outputJSONFormat($error, $msg, $total, $data = array(), $view = null) {

        $arrayFinal = [];
        $arrayBase = [
            'error' => $error,
            'msg' => $msg,
            'total' => $total
        ];
        $arrayPagination = ["pagination" => []];
        $arrayData = ["data" => []];

        if (array_key_exists('data', $data)) {
            $arrayData["data"] = $data["data"];
        }
        else {
            $arrayData["data"] = $data;
        }

        if (array_key_exists('total', $data)) {
            $arrayPagination["pagination"]["total"] = $data["total"];
        }
        else {
            $arrayPagination["pagination"]["total"] = count($arrayData["data"]);
        }

        if (array_key_exists('per_page', $data)) {
            $arrayPagination["pagination"]["per_page"] = $data["per_page"];
        }
        else {
            $arrayPagination["pagination"]["per_page"] = count($arrayData["data"]);
        }

        if (array_key_exists('current_page', $data)) {
            $arrayPagination["pagination"]["current_page"] = $data["current_page"];
        }
        else {
            $arrayPagination["pagination"]["current_page"] = 1;
        }

        if (array_key_exists('last_page', $data)) {
            $arrayPagination["pagination"]["last_page"] = $data["last_page"] > 0 ? $data['last_page'] : 1;
        }
        else {
            $arrayPagination["pagination"]["last_page"] = 1;
        }

        if (array_key_exists('from', $data)) {
            $arrayPagination["pagination"]["from"] = $data["from"];
        }
        else {
            $arrayPagination["pagination"]["from"] = 1;
        }

        if (array_key_exists('to', $data)) {
            $arrayPagination["pagination"]["to"] = $data["to"];
        }
        else {
            $arrayPagination["pagination"]["to"] = count($arrayData["data"]);
        }

        if (array_key_exists('metaData', $data)) {
            $arrayData["metaData"] = $data["metaData"];
        }

        $arrayFinal = array_merge($arrayBase, $arrayPagination, $arrayData);

        return $arrayFinal;
    }

    public static function validateData($modelName, $jsonObject){
        $object = $jsonObject;
        if(!is_array($jsonObject))
        {
            $object = json_decode(json_encode($jsonObject),true);
        }
                
        $model= new $modelName();
        $filters= $model->getFilters($model->getNameColumns());//se obtienen todos los filtros
        
        $validation = Validator::make($object, $filters);
        if( $validation->fails()){
            
            $messagesArray = $validation->errors()->all();
            $messagesString = implode(",", $messagesArray);
            
            throw new Exception($messagesString);
        }        
        return true;
    }  
    
    public static function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    public static function checkApplicationAccess($insUser, $application, $currentPath, $currentMethod)
    {
        //Retirar posibles {} en url
        $currentPath = preg_replace("/{|}/", "", $currentPath);

        //Obtener modulo
        $pathParts = explode('/',$currentPath);
        $module = $pathParts[0];

        //Obtener path para consulta en mongo
        $mongoPath = "actions.";
        if(count($pathParts) > 1) {
            //Retirar el primer elemento del arreglo porque ese es el modulo
            array_shift($pathParts);
            $mongoPath .= implode('.', $pathParts).".";
        }
        $mongoPath .= $currentMethod;

        //Recuperar el id de los roles que tiene asignado el usuario
        $projection = ["roles.id_role" =>1];
        $criteria = ['_id' => new \MongoDB\BSON\ObjectId($insUser->_id)];
        $collectionName = $insUser->getTable();
        $userPivotRoles = DB::collection($collectionName)->whereRaw($criteria)->project($projection)->get();
        $idRoles = [];
        foreach($userPivotRoles as $userPivotRole)
        {
            foreach ($userPivotRole['roles'] as $pivotRole)
            {
                if(is_array($pivotRole['id_role'])) { //Si es DBRef
                    $idRoles[] = $pivotRole['id_role']['$id'];
                }
                else {
                    $idRoles[] = new \MongoDB\BSON\ObjectId($pivotRole['id_role']);
                }
            }
        }

        if($application == 'API') {
            $criteria = ['$and' =>[['_id' => ['$in' => $idRoles]],['api.modules' => ['$elemMatch' => ['name' => $module, $mongoPath => true]]]]];
        }
        else {
            $criteria = ['$and' =>[['_id' => ['$in' => $idRoles]],['application.name' => $application],['application.modules' => ['$elemMatch' => ['name' => $module, $mongoPath => true]]]]];
        }

        $role = DB::collection('roles')->whereRaw($criteria)->first();

        $hasAccess = $role !== null;

        return $hasAccess;
    }

    public static function checkCompanyLicenses($insUser, $application, $currentPath, $request)
    {
        //Retirar posibles {} en url
        $currentPath = preg_replace("/{|}/", "", $currentPath);

        //Obtener modulo
        $pathParts = explode('/',$currentPath);
        $module = $pathParts[0];

        $idCompany = $insUser->id_company['$id'];

        $projection = ["licenses.$" =>1];
        $criteria = ['$and' =>[['_id' => $idCompany],['licenses.application.name' => $application],['licenses.application.modules.name' => $module]]];
        $license = DB::collection('companies')->whereRaw($criteria)->project($projection)->first();

        $hasLicense = $license !== null;

        return $hasLicense;
    }

    public static function arrayHasStringKeys(array $array) {
        return count(array_filter(array_keys($array), 'is_string')) > 0;
    }

    public static function saveFiles(Request $request, $path)
    {
        $result = [];
        $files = $request->files;
        foreach ($files  as $key => $file)
        {
            try {
                if (is_array($file)) {
                    $arrayTemp = [];
                    $subResult = [];
                    foreach ($file as $subfile) {
                        if ($subfile != null) {
                            $fileName = Util::moveFile($subfile, $path);
                            $subResult[] = $fileName;
                        }
                    }

                    $result[] = $subResult;
                }
                else {

                    $fileName = Util::moveFile($file, $path);
                    $pathImage = URL::to('/') . '/images/' . $fileName['fileName'];

                    $output_array = [];
                    preg_match("/tasks\/(.*)\/forms\/(.*)\/registers\/(.*)\/(c.*)/", $key, $output_array);
                    if(count($output_array) == 5)
                    {
                        $strIdTask = $output_array[1];
                        $strIdForm = $output_array[2];
                        $strIdRegister = $output_array[3];
                        $idField = $output_array[4];

                        $register = Register::where('id_task',['$ref' => 'tasks', '$id' =>new ObjectID($strIdTask)])
                            ->where('id_form',['$ref' => 'forms', '$id' =>new ObjectID($strIdForm)])
                            ->where('localId',$strIdRegister)
                            ->first();

                        $dataWeb = $register->dataWeb;
                        $dataWeb[$idField] = $pathImage;

                        $register->dataWeb = $dataWeb;

                        $register->save();
                    }
                    else {
						
						preg_match("/tasks\/(.*)\/images\/tasks\/(.*)\/(.*)/", $key, $output_array);
						Log::error($output_array);
						if(count($output_array) == 4)
						{
							$strIdTask = $output_array[1];
							$status = $output_array[3];

							$task = Task::where('_id',new ObjectID($strIdTask))->first();
							$task->{$status.'Photo'} = $pathImage;
							$task->save();
						}						
						else {
							preg_match("/tasks\/(.*)\/(.*)/", $key, $output_array);
							Log::error($output_array);
							if(count($output_array) == 3)
							{
								$strIdTask = $output_array[1];
								$status = $output_array[2];

								$task = Task::where('_id',new ObjectID($strIdTask))->first();
								$task->{$status.'Photo'} = $pathImage;
								$task->save();
							}
						}
                    }

                    $result[$key] = $fileName;
                }
            }
            catch(FileException $e){
                Log::error("Ocurrio un error al guardar la imagen ".$key);
                Log::error($e);
            }
        }

        return $result;
    }

    public static function moveFile($file,$path)
    {
        $prefijo = substr ( md5 ( uniqid ( rand () ) ), 0, 6 );
        $prefijo_min = substr ( md5 ( uniqid ( rand () ) ), 0, 6 );
        $archivo = $file->getClientOriginalName ();
        $info = explode('.',$archivo);
        $fileName = $prefijo_min . '_' .Util::sanearString($info[0]).'.'.$info[1];

        $resp = $file->move($path, $fileName );

        return ['fileName' => $fileName, 'filePath'=>$path];
    }

    public static function sanearString($string) {

        $string = trim($string);

        $string = str_replace(
            array('á', 'é ', 'í', 'ó', 'ú', 'ñ'),
            array('a', 'e', 'i', 'o', 'u', 'n'),
            $string
        );

        $string = str_replace(
            array('Á', 'É ', 'Í', 'Ó', 'Ó', 'Ñ'),
            array('A', 'E', 'I', 'O', 'U', 'N'),
            $string
        );

        $string = str_replace(
            array(' '),
            array('_'),
            $string
        );

        return $string;
    }

    public static function rutaAcciones($actions, $pathParts, $value)
    {
        if(count($pathParts) == 2) {
            if(!isset($actions[$pathParts[0]][$pathParts[1]])) {
                $actions[$pathParts[0]][$pathParts[1]] = $value;
            }
            else{
                $tempArray = $actions[$pathParts[0]][$pathParts[1]];
                $actions[$pathParts[0]][$pathParts[1]] = array_merge_recursive($tempArray, $value);
            }
            return $actions;
        }
        else if(count($pathParts) == 1) {
            if(!isset($actions[$pathParts[0]])) {
                $actions[$pathParts[0]] = $value;
            }
            else {
                $tempArray = $actions[$pathParts[0]];
                $actions[$pathParts[0]] = array_merge_recursive($tempArray, $value);
            }

            return $actions;
        }
        else {
            $key = array_pop($pathParts);

            $newValue = [];
            $newValue[$key] = $value;

            $newActions = Util::rutaAcciones($actions, $pathParts, $newValue);
            return $newActions;
        }
    }

    public static function extractFilters(array $filters, array &$result){
        foreach ($filters as $key => $value){
            if( is_numeric( $key )){
                array_push($result, $value);
            }else{
                if(is_array( $value)){
                    Util::extractFilters($value,$result);
                }
            }
        }
    }
    
    /**
     * remove special chars of string
     * @param unknown $string
     * @return mixed
     */
    public static function cleanString($string) {
   		$string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
   		return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
	}
	
	
	public static function getLocale(\App\Http\Requests\Request $request){
		$locale="en";
		if (Session::has('locale')) {
			$locale = Session::get('locale', Config::get('app.locale'));
		} else {
			$locale = substr($request->server('HTTP_ACCEPT_LANGUAGE'), 0, 2);
			 
			if ($locale != 'es' && $locale != 'en') {
				$locale = Config::get('app.locale');
			}
		}
		return $locale;
	}
}
