<?php
namespace App\Http\Controllers\Resources;

use App\Http\Controllers\GenericMongo\ControllerTraitRestore;
use App\Models\Resources\ResourceDefinition;
use App\Models\Resources\ResourceInstance;
use App\Models\Tracking\Actual;
use Carbon\Carbon;
use App\datatraffic\lib\Util;
use App\datatraffic\lib\Configuration;
use App\datatraffic\lib\ErrorMessages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Auth;
use App\Http\Controllers\GenericMongo\DatatrafficController;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\UTCDatetime;
use Crypt;
use Mail;
use App\Http\Controllers\GenericMongo\CompanyScope;
use \COM;
use App\Models\Users\Role;
use Illuminate\Validation\ValidationException;
use MongoDB\Exception\RuntimeException;

class ResourceInstanceController extends DatatrafficController
{
    use ControllerTraitRestore;

    //Nombre del modelo
    protected $modelo = 'App\Models\Resources\ResourceInstance';

    //Nombre de expirar
    protected $strExpireDate = 'expire';

    //Nombre del token
    protected $strExpireToken = 'token';

    //Nombre de eliminar
    protected $strDeletedAt = 'deleted_at';

    //Nombre de la contraseña
    protected $strPasswordName = 'password';

    //Nombre resetear password
    protected $strResetPassword = 'resetpassword';

    //Nombre del correo electrónico
    protected $strEmail = 'email';

    //Token Salt para la encriptación
    protected $strSalt = 'DataTr4FFi-C';

    //Token Salt para la encriptación
    protected $strDivider = '|';

    //Nombre Clave id de la compañía
    protected $strIdCompany = 'id_company';

    //URL para el reseteo de contraseña
    protected $urlResetPassword = '/resourceinstances/####/resetpassword/';

    //Titulo del reporte
    protected $excelTitle = 'Recursos';

    //Titulo del reporte
    protected $excelAttributes = '{"login":1,"email":1,"status":1,"company":{"name":1},"resourceDefinition":{"name":1},"resourceGroups":{"name":1},"deviceInstances":{"serial":1,"deviceDefinition":{"name":1}},"roles":{"role":{"name":1},"applicationName":1}}';
	
    /**
    public function store(Request $request)
    {
        $response = parent::store($request);
        $json = $response->getContent();
        $data = json_decode($json,true);
        if(!$data['error']) {
            //Recuperar el recurso
            $idResourceInstance = $data['data']['reference'];
            $resourceInstance = ResourceInstance::find($idResourceInstance);

            if($resourceInstance) {
                $this->uncheckDeviceInstances($resourceInstance);
                $this->checkDeviceInstances($resourceInstance);
            }
        }

        return $response;
    }
    */

    public function update(Request $request, $strId)
    {
        //Recuperar informacion orignial
        $resourceInstanceOld = ResourceInstance::find($strId);
        if(!$resourceInstanceOld) {
            $e = new ModelNotFoundException();
            $e->setModel(get_class($resourceInstanceOld));
            throw $e;
        }

        //Actualizar
        $response = parent::update($request, $strId);
        $json = $response->getContent();
        $data = json_decode($json,true);
        if(!$data['error']) {
            //Recuperar el recurso
            $resourceInstanceObj = new ResourceInstance();
            $resourceInstanceNew = $resourceInstanceObj->where('_id','=',new ObjectID($strId))->first();

            if($resourceInstanceNew) {
                $this->uncheckDeviceInstances($resourceInstanceNew);
                $this->checkDeviceInstances($resourceInstanceNew);
                $this->updateActual($resourceInstanceNew);
                if($resourceInstanceNew->status == 'inactive'){
                    $this->deleteResourceAccessToken($resourceInstanceNew);
                }

                //Encontrar los dispositivos que se removieron y eliminar los tokens
                $deviceInstanceDBRefsOld = $resourceInstanceOld->deviceInstances;
                $deviceInstanceDBRefsNew = $resourceInstanceNew->deviceInstances;
                $deviceInstanceDBRefsRemoved = array_udiff($deviceInstanceDBRefsOld, $deviceInstanceDBRefsNew, array($this, 'compareDBRef'));
                $this->deleteResourceDeviceAccessToken($resourceInstanceNew, $deviceInstanceDBRefsRemoved);
            }
        }

        return $response;
    }

    public function compareDBRef($a, $b)
    {
        return $a['$id'] === $b['$id'];
    }
	
    /**
     * Desmarcar los dispositivos que tenia el recurso como no usados 
     * @param unknown $resourceInstance
     */
    private function uncheckDeviceInstances($resourceInstance) {
        $updateArray = ['$set' => ['isUsed' => false], '$unset' => ['id_resourceInstance' => '']];
        DB::collection('deviceInstances')->where('id_resourceInstance', '=', $resourceInstance->getDBRef())->update($updateArray);
    }

    private function checkDeviceInstances($resourceInstance) {
        //Marcar los dispositivos como usados
        $deviceInstanceIds = [];
        $deviceInstanceDBRefs = $resourceInstance->deviceInstances;
        if (!empty($deviceInstanceDBRefs)) {
            foreach ($deviceInstanceDBRefs as $deviceInstanceDBRef) {
                $deviceInstanceIds[] = $deviceInstanceDBRef['$id'];
            }
            if (!empty($deviceInstanceIds)) {
                $updateArray = ['$set' => ['isUsed' => true, 'id_resourceInstance' => $resourceInstance->getDBRef()]];
                DB::collection('deviceInstances')->whereIn('_id', $deviceInstanceIds)->update($updateArray);
            }
        }
    }

    private function updateActual($resourceInstance){
        $actual = Actual::where('resourceInstance._id','=',new ObjectId($resourceInstance->_id))->first();

        if($actual){
            $newResourceInstance = $resourceInstance->replicate();
            $newResourceInstance->_id = $resourceInstance->_id;
            $hiddenFields = $newResourceInstance->getHidden();
            foreach ($hiddenFields as $hiddenField) {
                $newResourceInstance->$hiddenField = null;
            }
            $actual->resourceInstance()->save($newResourceInstance);
        }
    }

    private function deleteResourceAccessToken($resourceInstance) {
        //Deshabilitar tokens para este recurso
        DB::collection('refreshTokens')->whereNull('deleted_at')->where('id_resourceInstance', $resourceInstance->getDBRef())->update(['$currentDate' => ['deleted_at' => true]]);
    }

    private function deleteResourceDeviceAccessToken($resourceInstance, $deviceInstanceDBRefsRemoved) {
        if (!empty($deviceInstanceDBRefsRemoved)) {
            foreach ($deviceInstanceDBRefsRemoved as $deviceInstanceDBRef) {
                //Deshabilitar tokens para este recurso
                DB::collection('refreshTokens')->whereNull('deleted_at')->where('id_resourceInstance', $resourceInstance->getDBRef())->where('id_deviceInstance', $deviceInstanceDBRef)->update(['$currentDate' => ['deleted_at' => true]]);
            }
        }

    }

    public function getActualResourceInstance(Request $request) {
        $actualUser = Util::$insUser;
        $data = $actualUser->toArray();

        $intCode = 200;
        return response($data, $intCode);
    }

    public function destroy($strId)
    {
        $response = parent::destroy( $strId);
        $json = $response->getContent();
        $data = json_decode($json,true);
        if(!$data['error']) {
            //Recuperar el recurso
            $resourceInstanceObj = new ResourceInstance();
            $resourceInstance = $resourceInstanceObj->newQueryWithoutScope(new SoftDeletingScope())->where('_id','=',new ObjectID($strId))->first();
            if($resourceInstance) {
                $this->uncheckDeviceInstances($resourceInstance);
                $resourceInstance->deviceInstances = [];
                $resourceInstance->save();
                $this->deleteResourceAccessToken($resourceInstance);
            }
        }

        return $response;
    }

    public function restore($strId)
    {
        $error = true;
        $msg = trans('general.GENERAL_ERROR');
        $total = 0;
        $data = [];
        $view = [];
        $intCode = 500;

        //Recuperar recurso eliminado
        $resourceInstanceObj = new ResourceInstance();
        $resourceInstanceDeleted = $resourceInstanceObj->newQueryWithoutScope(new SoftDeletingScope())->where('_id','=',new ObjectID($strId))->first();
        if($resourceInstanceDeleted) {
            //Verificar que no haya otro recurso creado con el mismo login
            $login = $resourceInstanceDeleted->login;
            $resourceInstance = $resourceInstanceObj->where('login','=',$login)->where('_id','<>',new ObjectID($strId))->first();
            if(!$resourceInstance) {
                $resourceInstanceDeleted->restore();

                $error = false;
                $msg = trans('general.MSG_OK');
                $total = 1;
                $intCode = 200;
            }
            else {
                $msg = trans('general.duplicated_login');
            }
        }
        else {
            $e = new ModelNotFoundException();
            $e->setModel(get_class($resourceInstanceObj));
            throw $e;
        }

        $result = Util::outputJSONFormat($error, $msg, $total, $data, $view);
        return response($result, $intCode);
    }

    /**
     * Cambia la contraseña de un usuario
     */
    public function changepassword($strId)
    {
        $error = true;
        $msg = trans('general.GENERAL_ERROR');
        $total = 0;
        $data = [];
        $view = null;


        //Obtenemos el contenido de la solicitud
        $insRequest = Input::instance();
        $arrData = $insRequest->json()->all();

        if(!empty($arrData[$this->strPasswordName])){
            //Extraemos la contraseña
            $strPasswordData = $arrData[$this->strPasswordName];

            //Cambiamos la contraseña
            if($this->dChangePassword($strId, $strPasswordData)){
                $error = false;
                $msg = trans('general.MSG_OK');
                $data = ["reference" => $strId];
                $total = 1;
            }else{
                $msg = trans('general.MISSING_DATA_PARAMETER');
            }
        }else{
            $msg = trans('general.MISSING_DATA_PARAMETER');
        }

        //retornamos
        $result = Util::outputJSONFormat($error, $msg, $total, $data, $view);
        return $result;
    }

    /** Despliega la vista para el reseteo de la contraseña
     * @param Request $request
     * @param $strTokenOrId
     * @return mixed 
     */
    public function displayResetPasswordView(Request $request, $strTokenOrId)
    {
        $intCode = 200;
        $result = $this->dResetPasswordView($strTokenOrId);

        return response($result, $intCode);
    }

	/*
	 * Envía un correo que permite recuperar la contraseña
	 *
	 */
	public function recoveryPassword(Request $request)
	{
		$intCode = 500;
        $error = true;
        $msg = trans('general.GENERAL_ERROR');
        $total = 0;
        $data = [];
        $view = null;
		
		//Arreglo de informacion
        $arrData = $request->json()->all();
		
		if(!empty($arrData['login']))
		{	
			//Buscamos al usuario
			$objModel = new ResourceInstance();
			$arrModels = $objModel->withoutGlobalScope(new CompanyScope())
						 ->where('login', $arrData['login'])
						 ->get();
			
			if(count($arrModels)==1)
			{
				$arrModel = $arrModels->toArray()[0];
				$strId = $arrModel['_id'];
				
				$isSent = $this->dResetPasswordSend($strId);
				
				if($isSent)
				{
					//Armamos la respuesta
					$intCode = 200;
					$error = false;
					$msg = trans('general.MSG_OK');
					$data = ["reference" => $strId];
					$total = 1;
				}
			}
			else
				if(count($arrModels)>1){
					$msg = trans('general.LOGIN_AMBIGUOUS');
				}else{
					$msg = trans('general.LOGIN_NOT_FOUND');
				}
		}else{
			$msg = trans('general.DATA_PARAMETER_MISSING');
		}

        $result = Util::outputJSONFormat($error, $msg, $total, $data, $view);

        return response($result, $intCode);
	}
	
    public function sendTokenForResetPassword(Request $request, $strTokenOrId)
    {
        $intCode = 500;
        $error = true;
        $msg = trans('general.GENERAL_ERROR');
        $total = 0;
        $data = [];
        $view = null;

        //try
        //{
            $isSent = $this->dResetPasswordSend($strTokenOrId);
        //}
        /*catch(\Exception $e)
        {
            $isSent = false;
        }*/

        if($isSent)
        {
            //Armamos la respuesta
            $intCode = 200;
            $error = false;
            $msg = trans('general.MSG_OK');
            $data = ["reference" => $strTokenOrId];
            $total = 1;
        }

        $result = Util::outputJSONFormat($error, $msg, $total, $data, $view);

        return response($result, $intCode);
    }
    /**
     * Resetea la contraseña para que el usuario la pueda recuperar desde una url
     */
    public function resetpassword(Request $request, $strTokenOrId)
    {
        $intCode = 500;
        $error = true;
        $msg = trans('general.GENERAL_ERROR');
        $total = 0;
        $data = [];
        $view = null;

        $isReset = $this->dResetPasswordProcess($strTokenOrId);

        if($isReset)
        {
            //Armamos la respuesta
            $intCode = 200;
            $error = false;
            $msg = trans('general.MSG_OK');
            $total = 1;
        }

        $result = Util::outputJSONFormat($error, $msg, $total, $data, $view);

        return response($result, $intCode);
    }

    /**
     * Envia un correo a la propiedad email del modelo
     */
    private function dSendEmail(&$insModel)
    {
        $return = false;



        if(!empty($insModel->{$this->strEmail})){
            $strContentEmail = 'Para resetear su contraseña haga click en la url: ' . url($this->urlResetPassword);
            $strContentEmail = str_replace("####", $this->dGenerateTokenURL($insModel), $strContentEmail);

            //Enviamos el correo (https://laravel.com/docs/5.1/mail)
            Mail::raw($strContentEmail, function ($message) use ($insModel) {
                $strFullName = $insModel->name .' '. $insModel->lastname;

                $message->from('datatraffic123@gmail.com', 'FieldVision');
                $message->to($insModel->email, $strFullName)->subject('Recupere su Contraseña!');
            });

            $return = true;
        }

        return $return;
    }

    /**
     * Cambia la contraseña de un usuario dado el id y la contraseña
     */
    private function dChangePassword($strId, $strPasswordData)
    {
        $result = false;

        //Obtenemos el modelo
        $insModel = $this->dGetResourceInstanceFromId($strId);

        if(!empty($insModel)){
            //Cambiamos la contraseña
            $insModel->{$this->strPasswordName} = Hash::make($strPasswordData);
            $insModel->save();

            $result = true;
        }

        return $result;
    }

    /**
     * Agregamos la solicitud en el usuario y enviamos el correo
     */
    private function dResetPasswordSend($strId)
    {
        $intNroCharToken = 15;
        $intIncrementDateSecs = Carbon::now()->addDay();
        $result = false;

        //Obtenemos el modelo
        $insModel = $this->dGetResourceInstanceFromId($strId);

        if(!empty($insModel)){
            //Asignamos el expirar
            $insModel->{$this->strResetPassword} =
                [
                    $this->strExpireDate => new UTCDatetime($intIncrementDateSecs->getTimestamp() * 1000),
                    $this->strExpireToken => substr ( md5 ( uniqid ( rand () ) ), 0, $intNroCharToken )
                ];
            $insModel->save();

            //Enviamos el correo
            $result = $this->dSendEmail($insModel);
        }

        return $result;
    }
    /**
     * obtiene el modelo asociado al controlador
     * @return string
     */
    public function getModelo(){
    	return $this->modelo;
    }

    /**
     * Verificamos el token y mostramos la vista
     */
    private function dResetPasswordView($strTokenEncrypt)
    {
        $arrTokenData = $this->dDecodeTokenURL($strTokenEncrypt);
        $arrData = ['error' => 'true', 'msg' => 'Su token no es válido', 'url' => url($this->urlResetPassword)];

        //Verificamos que esten los elementos
        if(count($arrTokenData) != 3){
            return 'Error Token no válido';
        }else{
            //Obtenemos los datos
            $strId = $arrTokenData[0];
            $strSalt = $arrTokenData[1];
            $strToken = $arrTokenData[2];

            if($strSalt == $this->strSalt){
                //Obtenemos el modelo
                $insModel = $this->dGetResourceInstanceFromId($strId);

                //Verificamos que exista el usuario
                if(!empty($insModel) && $insModel->getIdPrimaryKey() == $strId){
                    //verificamos que sea un token válido
                    $strExpireToken = $insModel->{$this->strResetPassword}[$this->strExpireToken];

                    if($strExpireToken == $strToken){
                        //Verificamos que no allá expirado el token
                        $insMongoDateExpire = $insModel->{$this->strResetPassword}[$this->strExpireDate];
                        $insDateExpire = Carbon::createFromTimestamp($insMongoDateExpire->toDateTime()->getTimestamp());
                        $insDateNow = Carbon::now();

                        //Si está vigente el token
                        if($insDateNow->lte($insDateExpire)){
                            $arrData['error'] = 'false';
                            $arrData['msg']   = $strTokenEncrypt;
                        }else{
                            $arrData['msg'] = 'Su token expiró';
                        }
                    }
                }
            }
        }

        return view('resourceinstances.resetpassword', ['arrData' => $arrData]);
    }

    /**
     * Procesamos el cambio de contraseña si el token es correcto
     */
    private function dResetPasswordProcess($strTokenEncrypt)
    {
        $result = false;

        //Obtenemos el contenido de la solicitud
        $insRequest = Input::instance();
        $arrData = $insRequest->json()->all();
        $arrTokenData = $this->dDecodeTokenURL($strTokenEncrypt);

        //Verificamos que esten los elementos
        if(count($arrTokenData) == 3){
            //Obtenemos los datos
            $strId = $arrTokenData[0];
            $strSalt = $arrTokenData[1];
            $strToken = $arrTokenData[2];

            if($strSalt == $this->strSalt){
                //Obtenemos el modelo
                $insModel = $this->dGetResourceInstanceFromId($strId);

                //Verificamos que exista el usuario
                if(!empty($insModel) && $insModel->getIdPrimaryKey() == $strId){
                    //verificamos que sea un token válido
                    $strExpireToken = $insModel->{$this->strResetPassword}[$this->strExpireToken];

                    if($strExpireToken == $strToken){
                        //Verificamos que no allá expirado el token
                        $insMongoDateExpire = $insModel->{$this->strResetPassword}[$this->strExpireDate];
                        $insDateExpire = Carbon::createFromTimestamp($insMongoDateExpire->toDateTime()->getTimestamp());
                        $insDateNow = Carbon::now();

                        //Si está vigente el token
                        if($insDateNow->lte($insDateExpire)){

                            //Extraemos la contraseña
                            if(!empty($arrData[$this->strPasswordName])){
                                //Extraemos la contraseña
                                $strPasswordData = $arrData[$this->strPasswordName];

                                //Cambiamos la contraseña
                                if($result = $this->dChangePassword($strId, $strPasswordData)){
                                    //Eliminamos la propiedad expirar
                                    $strModelName = $this->modelo;
                                    $strModelName::where($insModel->getPrimaryKey(), $strId)->unset($this->strResetPassword);
                                }
                            }
                        }
                    }
                }
            }
        }

        //retornamos
        return $result;
    }

    /**
     * Algoritmo que se encarga de generar un token dado una instancia del usuario
     */
    private function dGenerateTokenURL(&$insModel)
    {
        $strSalt = $this->strSalt;
        $strId = $insModel->getIdPrimaryKey();
        $strToken = $insModel->{$this->strResetPassword}[$this->strExpireToken];

        $strSecret = $strId . $this->strDivider . $strSalt . $this->strDivider . $strToken;

        return Crypt::encrypt($strSecret);
    }

    /**
     * Decodifica el token recibido por la URL y envia un arreglo de elemntos desencriptados
     */
    private function dDecodeTokenURL($strTokenURLEncrypt)
    {
        $strTokenURL = Crypt::decrypt($strTokenURLEncrypt);
        $arrTokenDecrypt = explode($this->strDivider, $strTokenURL);

        return $arrTokenDecrypt;
    }

    /**
     * Obtenemos la instancia de un usuario dato un Id
     */
    private function dGetResourceInstanceFromId($strId)
    {
		$insModelTest = new ResourceInstance();
		$insModel = $insModelTest->withoutGlobalScope(new CompanyScope())
					 ->where('_id', $strId)
					 ->first();
					 
        return $insModel;
    }


    protected function getCustomAttributes($dataArray)
    {
        $customAttributes = [];

        //Si el usuario no tiene permiso para administrar todas las empresas
        //entonces se debe asignar como empresa la misma del usuario que inicio sesion
        if(!Util::$manageAllCompanies) {
            $DBRefCompany = Util::$insUser->id_company;
            $id_company = $DBRefCompany['$id']->__toString();

            $customAttributes['id_company'] = $id_company;
        }

        if(array_key_exists('password',$dataArray))
        {
            $customAttributes['password'] = Hash::make($dataArray['password']);
        }

        if(array_key_exists('login',$dataArray))
        {
            $customAttributes['login'] = strtolower($dataArray['login']);
        }

        return $customAttributes;
    }

    public function excel(Request $request) {
        //Tenemos que agregar los customAttributes en $excelAttributes
        if($request->has('filters')) {
            //Recuperar elementos del filtro
            $filterElements = [];
            $filters = json_decode(trim($request->get('filters')), true);
            Util::extractFilters($filters,$filterElements);

            //$excelAttributes
            $excelAttributes = json_decode($this->excelAttributes,true);

            //Recuperar atributos y aregarlos al $excelAttributes
            foreach ($filterElements as $filterElement){
                if($filterElement['field'] == 'id_resourceDefinition')
                {
                    $resourceDefinition = ResourceDefinition::where('_id','=',new ObjectID($filterElement['value']))->first();
                    if($resourceDefinition) {
                        $customAttributes = $resourceDefinition->customAttributes;
                        if(!empty($customAttributes)) {
                            foreach ($customAttributes as $customAttribute) {
                                $excelAttributes['customAttributes'][camel_case($customAttribute['fieldLabel'])] = 1;
                            }
                        }
                    }
                }
            }
            $this->excelAttributes = json_encode($excelAttributes);
        }

        return parent::excel($request);
    }
    
    /**
     * funcion que se ejecuta antes de guardar un modelo
     * se sobrescribe para validar la data antes de guardar
     * @param array $data
     */
    public function beforeStore( array &$data){
    	 if ( is_array( $data ) ) {
	    	 	if( array_key_exists( 'roles' , $data ) ){
	    	 		if( Util::$manageSystemRoles == false ){
	    	 		$roles=$data['roles'];
	    	 		$id_roles=[];
	    	 		foreach ($roles as $role){
	    	 			$id_roles[]= new ObjectID($role['id_role']);	
	    	 		}
	    	 		if( !empty($id_roles)){
	    	 			$count=Role::whereIn('_id',$id_roles)->where('isSystem', true)->count();
		    	 		if( $count > 0 ){
			    	 		throw new ValidationException('error.can_not_assign_system_role');
		    	 		}
	    	 		}
	    	 	}
    	 	}
    	 }
    }
    
    /**
     * funcion que se ejecuta despues de guardar un modelo
     */
    public function afterStore( $id ){
    	$resourceInstance = ResourceInstance::find( $id );    	
    	if( $resourceInstance ) {
    		$this->uncheckDeviceInstances($resourceInstance);
    		$this->checkDeviceInstances($resourceInstance);
    		
    		//inserta en actual
    		$actual = Actual::where('resourceInstance._id',$resourceInstance->_id)->first();
    		if( !$actual ){
    			$deviceDef= $resourceInstance->id_resourceDefinition;
    			$company= $resourceInstance->company;
    			$latitude=0;
    			$longitude=0;
    			$addres = "";
    			//Busca una posicion para asignar 
    			if($company){
    				$startEndLocations= $company->startEndLocations;
    				if( $startEndLocations ){
    					foreach ($startEndLocations as $startEndLocation ){
    						if($startEndLocation['id_resourceDefinition']['$id']==$deviceDef['$id']){
    							$latitude = $startEndLocation['start']['location']['lat'];
    							$longitude = $startEndLocation['start']['location']['lng'];
    							$addres = $startEndLocation['address'];
    						}
    					}
    				}
    				if( $latitude==0 || $longitude==0 ){
    					$city=$company->city;
    					$latitude  = $city->latitude;
    					$longitude = $city->longitude;
    					$addres    = $city->name;
    				}
    				if(is_null( $addres )){
    					$addres = "";
    				}
    			}
    			$actual = new Actual();
    			$actual->isVisible=false;
    			$actual->_class='co.com.datatraffic.fieldvision.tracking.Actual';
    			$actual->events=[];
    			$actual->latitude  = $latitude;
    			$actual->longitude = $longitude;
    			$actual->updateTime=Carbon::now('GMT-0');
    			$actual->speed=0;
    			$actual->address=$addres;
    			$actual->hasEvent=false;
    			$actual->distance=0;
    			$actual->virtualOdometer=0;
    			$actual->totalDistance=0;
    			$actual->isGPRS=true;
    			$actual->id_company=$resourceInstance->id_company;
    			$actual->save();
    			
    			$resource      = $resourceInstance->replicate();
    			$resource->_id = $resourceInstance->_id;
    			$actual->resourceInstance()->save( $resource  );
    		}
    	}
    	
    	
    	
    	
    }
    
    public function beforeUpdate( \Jenssegers\Mongodb\Eloquent\Model $model, array &$arrData, array $arrRelationsSynchronize){
    	if ( is_array( $arrData ) ) {
    		if( array_key_exists( 'roles' , $arrData ) ){
    			if( is_null( Util::$insUser ) || strcmp($model->_id."", Util::$insUser->_id.'')==0 ){
    				throw new RuntimeException('error.operation_not_permitted_on_same_user');
    			}    			
    			if( Util::$manageSystemRoles == false ){
    				$roles=$arrData['roles'];
    				$id_roles=[];
    				foreach ($roles as $role){
    					$id_roles[]= new ObjectID($role['id_role']);
    				}
    				if( !empty($id_roles)){
    					$count=Role::whereIn('_id',$id_roles)->where('isSystem', true)->count();
	    				if( $count > 0 ){
	    					throw new ValidationException('error.can_not_assign_system_role');
	    				}
    				}
    			}
    		}
    	}
    }
    
    /**
     * funcion que se ejecuta despues de actualizar un modelo
     */
    public function afterUpdate( $id ){
    
    }
    
    public function beforeDeleted( $id ){
    	//verificar que el usuario no se pueda eliminar a si mismo
    	if( is_null( Util::$insUser ) || strcmp($id, Util::$insUser->_id.'')==0 ){
    		throw new RuntimeException('error.operation_not_permitted_on_same_user');
    	}
    }
    
}
