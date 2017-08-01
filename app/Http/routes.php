<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//Autenticación
Route::group(['middleware' => ['log']], function() {
    Route::post('login', 'Login\LoginController@loginResource');
    Route::post('tracking/login', 'Login\LoginController@loginDevice');
    Route::post('login/refresh', 'Login\LoginController@refreshToken');
    Route::post('logout', 'Login\LoginController@logout');

    //Recursos -> Envía un correo para que el usuario pueda recuperar su contraseña
    Route::post('resourceinstances/recoverypassword/', 'Resources\ResourceInstanceController@recoveryPassword');

    //Recursos -> Resetea la contraseña (Muestra el view con un formulario para cambiar la contraseña - No puede estar en JWT)
    Route::get('resourceinstances/{strToken}/resetpassword/', 'Resources\ResourceInstanceController@resetpassword');
    //Recursos -> Resetea la contraseña (Cambia la contraseña - No puede estar en JWT)
    Route::put('resourceinstances/{strToken}/resetpassword/', 'Resources\ResourceInstanceController@resetpassword');

    //Recursos -> Resetea la contraseña (Muestra el view con un formulario para cambiar la contraseña - No puede estar en JWT)
    Route::get('resourceinstances/{strToken}/resetpassword/', 'Resources\ResourceInstanceController@displayResetPasswordView');
    //Recursos -> Resetea la contraseña (Cambia la contraseña - No puede estar en JWT)
    Route::put('resourceinstances/{strToken}/resetpassword/', 'Resources\ResourceInstanceController@resetpassword');

    Route::get('test', function (\Illuminate\Http\Request $originalRequest, \Illuminate\Routing\Router $router) {
        \App\datatraffic\lib\Util::$manageAllCompanies = true;
        $idUserUpdated = \App\Models\Resources\ResourceInstance::where('login', '=', 'pedrocaicedo')->get();
        $parentModel = \App\Models\Forms\Section::find("57e13871ea2b7b1544004023");
        $json = '{ "name": "SECCION 1", "id_form": "57e13863ea2b7b1544004022", "status": "active", "questions": ['
            . '{ "updated_at": "2016-09-20 14:15:30", "created_at": "2016-09-20 14:14:46", "order": 1, "hashtag": "Hashtag", "helptext": "Helptext",'
            . ' "xtype": "textfield", "configuration": { "fieldLabel": "1 d", "emptyText": "Default text...", "blankText": "This field is required", "allowBlank": false, "hidden": false,'
            . ' "hideEmptyLabel": true, "hideLabel": false, "readOnly": false, "value": "", "regex": "/[a-z_]/i", "regexText": "This field should only contain letters and _", "vtype": "alpha", "vtypeText": "This field should only contain letters and _", "minLength": 0, "maxLength": 20, "minLengthText": "The minimum length for this field is {0}", "maxLengthText": "The maximum length for this field is {0}", "validateBlank": false }, "cid": "c7", "id_user_update": "5787aae14499e32460034045", "user_update": "5787aae14499e32460034045" },'
            . ' { "updated_at": "2016-09-20 14:15:30", "created_at": "2016-09-20 14:14:46", "order": 2, "hashtag": "Hashtag", "helptext": "Helptext",'
            . ' "xtype": "textfield", "configuration": { "fieldLabel": "2 d", "emptyText": "Default text...", "blankText": "This field is required", "allowBlank": false, "hidden": false, "hideEmptyLabel": true, "hideLabel": false, "readOnly": false, "value": "", "regex": "/[a-z_]/i", "regexText": "This field should only contain letters and _", "vtype": "alpha", "vtypeText": "This field should only contain letters and _", "minLength": 0, "maxLength": 20, "minLengthText": "The minimum length for this field is {0}", "maxLengthText": "The maximum length for this field is {0}", "validateBlank": false }, "cid": "c11", "id_user_update": "5787aae14499e32460034045", "user_update": "5787aae14499e32460034045" }] }';
        $arrRelationsSincronize = ["questions"];
        $arrData = json_decode($json, true);
        $controller = new \App\Http\Controllers\Forms\SectionController();
        $controller->updateObject($parentModel, $arrData, $arrRelationsSincronize, $idUserUpdated);
    });

    Route::get('upload', function () {
        return view('upload');
    });
});

	

//Zona protegida por sessión (JWT - https://jwt.io/)
Route::group(['middleware' => ['log','api.auth','locale.lang']], function()
{
    //Batch
    Route::post('batch', 'Batch\BatchController@processBatchRequest');

    /**
     * Manejo de las Aplicaciones
     */

    //Aplicaciones
    Route::resource('applications', 'Applications\ApplicationController');

    /**
     * Manejo de los Roles
     */

    //Roles
    Route::resource('roles', 'Users\RoleController',  ['only' => ['index', 'show']] );

    /**
     * Manejo de las Compañías
     */

    //Compañias
    //Exportar a excel
    Route::get('companies/excel', 'Companies\CompanyController@excel');
    //Registrar
    Route::resource('companies', 'Companies\CompanyController');
    //Lista las licencias de una compañía
    Route::get('companies/{companies}/licenses', 'Companies\CompanyController@licensesList');
    //Guarda la licencia de una compañía (Acumula las licencias)
    Route::post('companies/{companies}/licenses', 'Companies\CompanyController@licensesSave');
    //Obtiene una licencia específica de una compañía
    Route::get('companies/{companies}/licenses/{strIdLicense}', 'Companies\CompanyController@licensesGet');
    //Actualiza una licencia específica de una compañía
    Route::put('companies/{companies}/licenses/{strIdLicense}', 'Companies\CompanyController@licensesUpdate');
    //Elimina una licencia específica de una compañía
    Route::delete('companies/{companies}/licenses/{strIdLicense}', 'Companies\CompanyController@licensesDelete');
    //Exportar a excel
    Route::get('companies/excel', 'Companies\CompanyController@excel');

    /**
     * Manejo de las Definiciones de Dispositivos
     */

    //Definición de Dispositivos
    Route::resource('devicedefinitions', 'Devices\DeviceDefinitionController');

    /**
     * Manejo de las Instancias de Dispositivos
     */

    //Definición de Dispositivos
    //Exportar a excel
    Route::get('deviceinstances/excel', 'Devices\DeviceInstanceController@excel');
    //Registrar
    Route::resource('deviceinstances', 'Devices\DeviceInstanceController');

    /**
     * Manejo de las Plantillas de Recurso
     */

    //Plantillas de Recursos
    Route::resource('resourcetemplates', 'Resources\ResourceTemplateController');

    /**
     * Manejo de las Definiciones de Recurso
     */

    //Definición de Recursos
    //Exportar a excel
    Route::get('resourcedefinitions/excel', 'Resources\ResourceDefinitionController@excel');
    //Registrar
    Route::resource('resourcedefinitions', 'Resources\ResourceDefinitionController');

    //Recursos
    //Exportar a excel
    Route::get('resourceinstances/excel', 'Resources\ResourceInstanceController@excel');
    //Registrar
    Route::resource('resourceinstances', 'Resources\ResourceInstanceController');
    //Recursos -> Restaura un Recurso
    Route::put('resourceinstances/{resourceinstances}/restore', 'Resources\ResourceInstanceController@restore');
    //Recursos -> Cambia la contraseña
    Route::put('resourceinstances/{resourceinstances}/changepassword', 'Resources\ResourceInstanceController@changepassword');
    //Recursos -> Resetea la contraseña (agrega el expirar y envia correo con url)
    Route::post('resourceinstances/{resourceinstances}/resetpassword', 'Resources\ResourceInstanceController@sendTokenForResetPassword');
    //Recursos -> Recurso actual
    Route::get('actualresourceinstance', 'Resources\ResourceInstanceController@getActualResourceInstance');

    //Tracking
    Route::get('tracking/actual/excel/{type}', 'Tracking\ActualController@excelCustom');
    Route::resource('tracking/actual', 'Tracking\ActualController');
    Route::get('tracking/history/excel', 'Tracking\HistoryController@excel');
    Route::resource('tracking/history', 'Tracking\HistoryController');
    Route::resource('tracking/positions', 'Tracking\PositionController');
    Route::resource('tracking/events', 'Tracking\EventController');
    Route::get('eventtypes', 'Tracking\EventTypeController@index');
    Route::get('eventtypes/{strIdRegister}', 'Tracking\EventTypeController@show');

    //Planning
    Route::resource('tasks', 'Planning\TaskController');
    Route::put('tasks/{strIdTask}/cancel', 'Planning\TaskController@cancel');
    Route::post('tasks/{strIdTask}/images', 'Planning\TaskController@saveTaskStatusPhoto');
    Route::post('tasks/{strIdTask}/forms/{strIdForm}/registers/{strIdRegister}', 'Forms\RegisterController@saveFromRequest');
    Route::resource('orders', 'Planning\OrderController');
    Route::resource('routes', 'Planning\RouteController');

    //Resource Groups
    Route::resource('resourcegroups', 'Resources\ResourceGroupController');

    //Controls
    Route::resource('geofences', 'Controls\GeofenceController');
    Route::resource('checkpoints', 'Controls\CheckPointController');
    Route::resource('speedlimits', 'Controls\SpeedLimitController');

    //Messages
    Route::resource('messages', 'Messages\MessageController');

    //Campains
    Route::get('forms/excel', 'Forms\FormController@excel');
    Route::resource('forms', 'Forms\FormController');

    Route::resource('sections', 'Forms\SectionController');
    Route::resource('registers', 'Forms\RegisterController');

    //Export
    Route::get('export/registers', 'Export\ExportController@exportRegister');
    Route::get('export/tracking/actual', 'Export\ExportController@exportTrackingActual');
    Route::get('export/planning/actual', 'Export\ExportController@exportPlanningActual');

    //Utilidades
    //Geocodificacion
    Route::get('addresses/validate', 'Planning\AddressController@validate');

    Route::post('positions', 'Tracking\PositionController@store');

    //Ciudades
    Route::resource('cities', 'Companies\CityController');

    //Paises
    Route::resource('countries', 'Companies\CountryController');

    //Scheduling
    Route::resource('scheduling/processes', 'Scheduling\ProcessController');
    Route::post('scheduling/processes/upload', 'Scheduling\ProcessController@upload');
    Route::post('scheduling/processes/{processes}/schedule', 'Scheduling\ProcessController@schedule');
    Route::post('scheduling/processes/{processes}/accept', 'Scheduling\ProcessController@accept');
    Route::resource('scheduling/temporaltasks', 'Scheduling\TemporalTaskController');
    Route::resource('scheduling/temporalroutes', 'Scheduling\TemporalRouteController');
    Route::resource('scheduling/temporalorders', 'Scheduling\TemporalOrderController');

    //Icons
    Route::get('icons', 'Icons\IconController@index');
}
);