<?php

/**
 * Controlador que proporciona logica para las acciones de login y logout.
 *
 * Controlador que proporciona logica para las acciones de login y logout.
 *
 * @version 2.0.0
 * @author Sergio Sinuco (v1.0.0 - Versión inicial.)
 * @author Pedro Caicedo (v2.0.0 - Refactory de la clase)
 * @license Datatraffic General License
 * @copyright Datatraffic S.A.S.
 */

namespace App\Http\Controllers\Login;

use App\Exceptions\MissingRequestParameter;
use App\Http\Controllers\Controller;
use App\datatraffic\lib\Util;
use App\Http\Controllers\GenericMongo\CompanyScope;
use App\Http\Middleware\APIAuth;
use App\Models\Devices\DeviceInstance;
use App\Models\Login\RefreshToken;
use App\Models\Resources\ResourceInstance;
use App\Models\Resources\ResourceTracking;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use JWTAuth;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Agent;
use Input;
use DB;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Log;

class LoginController extends Controller
{
    private function validateRequest(Request $request){
        //Obtenemos los datos enviados
        if (!$request->has('device')) {
            throw new MissingRequestParameter('No se especifico el parametro device');
        }

        if (!$request->has('application')) {
            throw new MissingRequestParameter('No se especifico el parametro application');
        }
    }

    private function getResourceInstance($login, $password){

        //Encontrar recurso
        $resourceInstanceObj = new ResourceInstance();
        $resourceInstanceQuery = $resourceInstanceObj->newQueryWithoutScopes()->where('login' , $login);
        $scope = new SoftDeletingScope();
        $scope->apply($resourceInstanceQuery, $resourceInstanceObj);
        $resourceInstance = $resourceInstanceQuery->first();
        if(!$resourceInstance)
        {
            throw new AuthenticationException('user_not_found');
        }

        //Verificar que la empresa del recurso no este eliminada
        $company = $resourceInstance->company;
        if(!$company) {
            throw new AuthenticationException('deleted_company');
        }

        //Verificar que la empresa del recurso este activa
        if($company->status === 'inactive') {
            throw new AuthenticationException(trans('error.inactive_company'));
        }

        //Verificar contraseña
        if(!Hash::check($password, $resourceInstance->getAuthPassword()))
        {
            throw new AuthenticationException( trans('error.invalid_credentials') );
        }

        //Verificar estado
        if($resourceInstance->status === 'inactive')
        {
            throw new AuthenticationException(trans('error.inactive_user'));
        }

        return $resourceInstance;
    }

    private function getResourceTracking($login, $password){
        $resourceTrackingObj = new ResourceTracking();
        $resourceTrackingQuery = $resourceTrackingObj->newQueryWithoutScopes()->where('login' , $login);
        $scope = new SoftDeletingScope();
        $scope->apply($resourceTrackingQuery, $resourceTrackingObj);
        $resourceTracking = $resourceTrackingQuery->first();
        if(!$resourceTracking)
        {
            throw new AuthenticationException('user_not_found');
        }

        return $resourceTracking;
    }

    private function getDeviceInstance($device){
        //Recuperar el dispositivo
        $deviceInstance = (new DeviceInstance())->withoutGlobalScope(new CompanyScope())->where('serial','=',$device)->first();
        if(!$deviceInstance) {
            throw new AccessDeniedHttpException('general.device_not_found');
        }

        //Verificar el estado del dispositivo
        if($deviceInstance->status === 'inactive') {
            throw new AccessDeniedHttpException('general.device_not_active');
        }

        return $deviceInstance;
    }

    private function checkAssignedDevice($deviceInstance, $resourceInstance){
        //Verificar que el dispositivo este asignado al recurso
        $deviceInstances = $resourceInstance->deviceInstances;
        return in_array($deviceInstance->getDBRef(),$deviceInstances);
    }

    private function getJWT($resourceInstance, $application){
        $company = $resourceInstance->company;
		//falta hacer match con el 
        $modules = $resourceInstance->getApplicationModules($application);
        $routingTool = $resourceInstance->getRoutingTool();
        $map = $company->getLocation();

        //Generar JWT
        $customClaims = [
            'login' => $resourceInstance->login,
            'id_company' => (string)$company->_id,
            'modules' => $modules,
            'routingTool' => $routingTool,
            'map' => $map
        ];
        $access_token = JWTAuth::fromUser($resourceInstance, $customClaims);

        return $access_token;
    }

    private function getRefreshToken(){
        return Util::generateRandomString(100);
    }

    private function saveToken($device, $application, $resourceInstance, $deviceInstance, $acessToken, $refreshToken)
    {
        $token = new RefreshToken();
        $token->application = $application;
        $token->device = $device;
        $token->accessToken = $acessToken;
        $token->refreshToken = $refreshToken;
        $token->id_resourceInstance = $resourceInstance->getDBRef();
        if($deviceInstance) {
            $token->id_deviceInstance = $deviceInstance->getDBRef();
        }
        $token->save();

        return $token;
    }

    public function loginResource(Request $request)
    {
        $this->validateRequest($request);

        $device = $request->get('device');
        $application = $request->get('application');
        $login = $request->get('email');
        $password = $request->get('password');

        //Obtener uri de la ruta actual
        $route = Route::getFacadeRoot()->current();
        $currentPath= $route->uri();
        $method = $request->getMethod();

        $resourceInstance = $this->getResourceInstance($login, $password);

        $hasLicense = Util::checkCompanyLicenses($resourceInstance, $application, $currentPath, $request);
        if(!$hasLicense) {
            throw new AuthenticationException('no_license');
        }

        $hasAccess =  Util::checkApplicationAccess($resourceInstance, $application, $currentPath, $method);
        if(!$hasAccess) {
            throw new AccessDeniedHttpException('general.acess_denied');
        }

        //Si se trata de la aplicacion movil verificar que tenga asignado el imei desde el cual se quiere inciar sesion
        $deviceInstance = null;
        if($application == 'com.datatraffic.formulariodinamico.app') {
            $deviceInstance = $this->getDeviceInstance($device);
            $isAssignedDevice = $this->checkAssignedDevice($deviceInstance, $resourceInstance);
            if(!$isAssignedDevice) {
                throw new AccessDeniedHttpException('general.device_not_assigned');
            }
        }

        $access_token = $this->getJWT($resourceInstance, $application);
        $refresh_token = $this->getRefreshToken();

        $this->saveToken($device, $application, $resourceInstance, $deviceInstance, $access_token, $refresh_token);

        //Respuesta
        $error = false;
        $msg = trans('general.MSG_OK');
        $total = 1;
        $data = ['data' => compact('access_token','refresh_token')];
        $view = null;
        $intCode = 200;
        $result = Util::outputJSONFormat($error, $msg, $total, $data, $view);

        return response($result, $intCode);
    }

    public function loginDevice(Request $request) {

        //$this->logRequest($request);

        $this->validateRequest($request);

        $device = $request->get('device');
        $application = $request->get('application');
        $login = $request->get('email');
        $password = $request->get('password');

        //Encontrar recurso
        $deviceInstance = $this->getDeviceInstance($login);
        $resourceTracking = $this->getResourceTracking($login, $password);

        //Generar JWT
        $access_token = $this->getJWT($resourceTracking, $application);

        $refresh_token = $this->getRefreshToken();

        $this->saveToken($device, $application, $resourceTracking, $deviceInstance, $access_token, $refresh_token);

        //Repuesta
        $error = false;
        $msg = trans('general.MSG_OK');
        $total = 1;
        $data = ['data' => compact('access_token','refresh_token')];
        $view = null;
        $intCode = 200;
        $result = Util::outputJSONFormat($error, $msg, $total, $data, $view);

        return response($result, $intCode);
    }

    public function refreshToken (Request $request)
    {
        $this->logRequest($request);

        $this->validateRequest($request);

        if (!$request->has('refresh_token')) {
            throw new MissingRequestParameter('No se especifico el parametro refresh_token');
        }

        $device = $request->get('device');
        $application = $request->get('application');

        $refresh_token = $request->get('refresh_token');
        $refreshToken = RefreshToken::where('device','=',$device)
            ->where('application','=',$application)
            ->where('refreshToken','=',$refresh_token)
            ->first();

        if(!$refreshToken) {
            throw new TokenExpiredException('token_deleted');
        }

        //Verificar que el acceso token sea valido
        $access_token = $refreshToken->accessToken;
        $apiAuth = new APIAuth();
        try {
            $apiAuth->getUserFromToken($access_token,false);
        }
        catch(TokenExpiredException $e) {
            $access_token = JWTAuth::parseToken()->refresh();
        }

        $refreshToken->accessToken = $access_token;
        $refreshToken->save();

        //Repuesta
        $error = false;
        $msg = trans('general.MSG_OK');
        $total = 1;
        $data['data'] = compact('access_token','refresh_token');
        $view = null;
        $intCode = 200;
        $result = Util::outputJSONFormat($error, $msg, $total, $data, $view);

        return response($result, $intCode);
    }

    public function logout ($strToken = null)
    {
        //Obtenemos el token
        if(!$strToken) {
            $strToken = JWTAuth::getToken();
        }

        //Desautorizamos el token
        JWTAuth::invalidate($strToken);

        $refreshToken = RefreshToken::where('accessToken','=',$strToken)->first();
        if(!$refreshToken) {
            $refreshToken->delete();
        }

        //Repuesta
        $error = false;
        $msg = trans('general.MSG_OK');
        $total = 1;
        $data['data'] = [];
        $view = null;
        $intCode = 200;
        $result = Util::outputJSONFormat($error, $msg, $total, $data, $view);

        return response($result, $intCode);
    }
}