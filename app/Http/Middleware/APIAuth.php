<?php

namespace App\Http\Middleware;

use App\Models\Login\RefreshToken;
use App\Models\Resources\ResourceInstance;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Closure;
use Illuminate\Support\Facades\Log;
use JWTAuth;
use App\datatraffic\lib\Util;
use Route;
use DB;
use App\Models\Resources\ResourceTracking;
use MongoDB\BSON\ObjectID;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Providers\JWT\NamshiAdapter;

class APIAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        //Obtener uri, metodo y token de la ruta actual
        $routes = \Illuminate\Support\Facades\Route::getRoutes();
        $route = $routes->match($request);
        $currentPath= $route->getUri();
        $method = $request->getMethod();
        if($request->hasHeader('Authorization')){
            $header = $request->header('Authorization');
            $token = trim(str_ireplace('bearer', '', $header));
        }
        else {
            $token = $request->get('token');
        }
        $insUser = $this->getUserFromToken($token, false);
        $this->checkUserPermissions($insUser, $currentPath, $method);

        //Dejamos pasar la solicitud/petición
        $response = $next($request);

        return $response;
    }

    public function getUserFromToken($token, $ignoreTokenExpiredException) {
        //Verificar que el token este activo
        $accesToken = RefreshToken::where('accessToken',$token)->first();
        if(!$accesToken){
            throw new TokenExpiredException('token_deleted');
        }

        //Colocar el permiso de ver todas las empresas para poder validar el token
        Util::$manageAllCompanies = true;

        //Verificar token
        try{
            $insUser = JWTAuth::setToken($token)->authenticate();
        }
        catch (TokenExpiredException $e){
            //Si el token ya expiro pero se ignora esta excepcion buscar el recurso
            if($ignoreTokenExpiredException){
                $namshiAdapter = new NamshiAdapter(config('jwt.secret'), config('jwt.aglo'));
                $sub = $namshiAdapter->decode($token)['sub'];
                $insUser = ResourceInstance::where('_id','=',new ObjectID($sub))->first();
            }
            else {
                throw $e;
            }
        }

        if (!$insUser) {
            //Si no encuentra usuario, entonces verificar que sea una tablet
            $payload = JWTAuth::getPayload($token);
            $sub = $payload->get('sub');
            $resourceTracking = new ResourceTracking();
            $resourceTracking = $resourceTracking->where('_id','=',new ObjectID($sub))->first();
            if ($resourceTracking) {
                $insUser = $resourceTracking;
            }
            else {
                throw new AuthenticationException('general.user_not_found');
            }
        }

        //Verificar que la empresa del recurso no este eliminada
        $company = $insUser->company;
        if(!$company) {
            throw new AuthenticationException('deleted_company');
        }

        //Verificar que la empresa del recurso este activa
        if($company->status === 'inactive') {
            throw new AuthenticationException('inactive_company');
        }

        //Verificar estado
        if($insUser->status === 'inactive') {
            throw new AuthenticationException('inactive_user');
        }

        //Retornar permiso a que no pueda ver ninguna empresa
        Util::$manageAllCompanies = false;

        return $insUser;
    }

    public function checkUserPermissions($insUser, $currentPath, $method)
    {
        //Verificar acceso
        $hasAccess = Util::checkApplicationAccess($insUser, 'API', $currentPath, $method);
        if (!$hasAccess) {
            throw new AccessDeniedHttpException('general.acess_denied');
        }

        //Verificar acceso a getAllCompanies, postAllCompanies, putAllCompanies, deleteAllCompanies
        Util::$manageAllCompanies = Util::checkApplicationAccess($insUser, 'API', 'manageallcompanies', $method);
        Util::$manageAllResource = Util::checkApplicationAccess($insUser, 'API', 'manageallresources', $method);
        Util::$manageAllCreated = Util::checkApplicationAccess($insUser, 'API', 'manageallcreated', $method);
        Util::$viewErrors = Util::checkApplicationAccess($insUser, 'API', 'viewerrors', $method);
        Util::$manageSystemRoles = Util::checkApplicationAccess($insUser, 'API', 'managesystemroles', $method);
        
        //Guardamos el usuario
        Util::$insUser = $insUser;

        return true;
    }
}
