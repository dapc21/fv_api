<?php
namespace App\Http\Controllers\Devices;

use App\datatraffic\lib\Util;
use App\Http\Controllers\GenericMongo\DatatrafficController;
use App\Http\Controllers\GenericMongo\ControllerTraitCompanyCustomAttribute;
use Illuminate\Http\Request;
use App\Models\Devices\DeviceInstance;
use App\Models\Resources\ResourceTracking;
use App\Models\Users\PivotRole;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;
use MongoDB\BSON\ObjectID;

class DeviceInstanceController extends DatatrafficController
{
    //Nombre del modelo
    protected $modelo = 'App\Models\Devices\DeviceInstance';

    //Titulo del reporte
    protected $excelTitle = 'Dispositivos';

    //Titulo del reporte
    protected $excelAttributes = '{"serial":1,"deviceDefinition":{"name":1},"company":{"name":1},"resourceInstance":{"login":1},"status":1}';

    protected function getCustomAttributes($dataArray)
    {
        $customAttributes = [];

        //Si el usuario no tiene permiso para administrar todas las empresas
        //entonces se debe asignar como empresa la misma del usuario que inicio sesion
        if(!Util::$manageAllCompanies) {
            $DBRefCompany = Util::$insUser->id_company;
            $id_company = $DBRefCompany['$id']->__toString();

            $customAttributes = ['id_company' => $id_company];
        }

        $customAttributes['isUsed'] = false;

        return $customAttributes;
    }

    public function store(Request $request)
    {
        $response = parent::store($request);
        $json = $response->getContent();
        $data = json_decode($json,true);
        if(!$data['error']) {
            //Recuperar el recurso
            $strId = $data['data']['reference'];
            $deviceInstanceObj = new DeviceInstance();
            $deviceInstance = $deviceInstanceObj->newQueryWithoutScope(new SoftDeletingScope())->where('_id','=',new ObjectID($strId))->first();
            if($deviceInstance) {
                //Guardar el recurso
                $resourceTrackingObj = new ResourceTracking();
                $resourceTrackingObj->login = $deviceInstance->serial;
                $resourceTrackingObj->email = $deviceInstance->serial;
                $resourceTrackingObj->status = 'active';
                $resourceTrackingObj->id_company = $deviceInstance->id_company;
                $resourceTrackingObj->save();

                $pivotRole = new PivotRole();
                $pivotRole->applicationName = "com.datatraffic.formulariodinamico.app";
                $pivotRole->roleName = "Usuario movil cliente";
                $pivotRole->id_role = ['$ref' => "roles", '$id' => new ObjectID("57b8c2161d69b02524003ef3")];
                $resourceTrackingObj->roles()->save($pivotRole);
            }
        }

        return $response;
    }

    public function update(Request $request, $strId)
    {
        $response = parent::update($request, $strId);
        $json = $response->getContent();
        $data = json_decode($json,true);
        if(!$data['error']) {
            //Recuperar el recurso
            $deviceInstanceObj = new DeviceInstance();
            $deviceInstance = $deviceInstanceObj->newQueryWithoutScope(new SoftDeletingScope())->where('_id','=',new ObjectID($strId))->first();
            if($deviceInstance) {
                if($deviceInstance->status == 'inactive') {
                    //Deshabilitar tokens para este dispositivo
                    $this->deleteAccessToken($deviceInstance);
                }
            }
        }

        return $response;
    }

    public function destroy($strId)
    {
        $response = parent::destroy( $strId);
        $json = $response->getContent();
        $data = json_decode($json,true);
        if(!$data['error']) {
            //Recuperar el recurso
            $deviceInstanceObj = new DeviceInstance();
            $deviceInstance = $deviceInstanceObj->newQueryWithoutScope(new SoftDeletingScope())->where('_id','=',new ObjectID($strId))->first();
            if($deviceInstance) {
                //Eliminar el dispositivo del recurso
                $resourceInstance = $deviceInstance->id_resourceInstance;
                if($resourceInstance){
                    DB::collection('resourceInstances')->where('_id', '=', $resourceInstance['$id'])->pull('deviceInstances', $deviceInstance->getDBRef());
                }
                //Eliminar el recurso para hacer tracking del dispositivo
                ResourceTracking::where('login','=',$deviceInstance->serial)->delete();
                //Deshabilitar tokens para este dispositivo
                $this->deleteAccessToken($deviceInstance);
            }
        }

        return $response;
    }

    private function deleteAccessToken($deviceInstance) {
        //Deshabilitar tokens para este recurso
        DB::collection('refreshTokens')->whereNull('deleted_at')->where('id_deviceInstance', $deviceInstance->getDBRef())->update(['$currentDate' => ['deleted_at' => true]]);
    }
}
