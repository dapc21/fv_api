<?php
namespace App\Http\Controllers\Batch;

use App\datatraffic\lib\Util;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Log;
use \App\Http\Middleware\APIAuth;

class BatchController extends Controller
{
    public function processBatchRequest(Request $batchRequest)
    {
        if (!$batchRequest->has('data'))
        {
            throw new \Exception("No se especifico el registro");
        }
        $json = utf8_encode($batchRequest->get('data'));
        $arrayFromJson = json_decode($json, true);
        $requestArray  = $arrayFromJson['requests'];

        $tokenBatchRequest = trim(str_ireplace('bearer', '', $batchRequest->header('Authorization')));

        $data = [];
        foreach ($requestArray as $requestItem) {

            $path = $requestItem['path'];
            $method = $requestItem['method'];
            $headers = $requestItem['headers'];
            $body = $requestItem['body'];
            $server = ['CONTENT_TYPE' => 'application/json'];

            try {
                //Crear la nueva peticion
                $request = $batchRequest->create($path, $method, [], [], [], $server, $body);
                $route = Route::getRoutes()->match($request);
                $routePath = $route->getUri();
                $action = $route->getAction();
                $actionController = explode('@',$action['controller']);

                //Obtener el nuevo token
                if(array_key_exists('Authorization',$headers)){
                    $token = trim(str_ireplace('bearer', '', $headers['Authorization']));
                    $ignoreTokenExpiredException = true;
                }
                else {
                    $token = $tokenBatchRequest;
                    $ignoreTokenExpiredException = false;
                }

                //Validar nuevo token
                $apiAuth = new APIAuth();
                $insUser = $apiAuth->getUserFromToken($token, $ignoreTokenExpiredException);
                $apiAuth->checkUserPermissions($insUser, $routePath, $method);

                $controller = new $actionController['0'];
				$controllerMethod = $actionController['1'];
                if ($method == 'POST') {
					if($controllerMethod == 'saveFromRequest'){
						$output_array = [];
						preg_match("/tasks\/(.*)\/forms\/(.*)\/registers\/(.*)/", $path, $output_array);
						$strIdTask = $output_array[1];
						$strIdForm = $output_array[2];
						$strIdRegister = $output_array[3];
						$response = $controller->saveFromRequest($request, $strIdTask, $strIdForm, $strIdRegister);

						$jsonResponse = $response->getContent();
						$arrayReponse = json_decode($jsonResponse,true);
						if(!$arrayReponse['error']){
							$data[] = $arrayReponse['data'];
						}						
					}
					else{
						$response = $controller->store($request);

						$jsonResponse = $response->getContent();
						$arrayReponse = json_decode($jsonResponse,true);
						if(!$arrayReponse['error']){
							$data[] = $arrayReponse['data'];
						}
					}
                } else if ($method == 'PUT') {
                    $info = explode('/', $path);
                    $strId = $info[count($info) - 1];
                    $response = $controller->update($request, $strId);

                    $jsonResponse = $response->getContent();
                    $arrayReponse = json_decode($jsonResponse,true);
                    if(!$arrayReponse['error']){
                        $data[] = $arrayReponse['data'];
                    }
                } else if ($method == 'DELETE') {
                    $info = explode('/', $path);
                    $strId = $info[count($info) - 1];
                    $response = $controller->delete($request, $strId);

                    $jsonResponse = $response->getContent();
                    $arrayReponse = json_decode($jsonResponse,true);
                    if(!$arrayReponse['error']){
                        $data[] = $arrayReponse['data'];
                    }
                }
            }
            catch (\Exception $e) {
                Log::error($e);
            }
        }
        $path = public_path('images/');
        Util::saveFiles($batchRequest, $path);

        $error = false;
        $msg = trans('general.MSG_OK');
        $total = 1;
        $intCode = 201;

        $result = Util::outputJSONFormat($error, $msg, $total, $data, []);
        return response($result, $intCode);
    }
}