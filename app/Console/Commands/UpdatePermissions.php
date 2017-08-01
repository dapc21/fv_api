<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdatePermissions extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'UpdatePermissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza aplicaicones, roles y licencias';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        //Encontrar modulos API
        $routeCollection = \Illuminate\Support\Facades\Route::getRoutes();

        $modulesAPI = [];
        foreach ($routeCollection as $value) {
            $currentPath = $value->getPath();

            //Retirar posibles {} en url
            $currentPath = preg_replace("/{|}/", "", $currentPath);

            //Obtener metodo HTTP de la peticion
            $currentMethods = $value->getMethods();

            //Obtener modulo
            $pathParts = explode('/', $currentPath);
            $moduleName = array_shift($pathParts);

            if (!array_key_exists($moduleName, $modulesAPI)) {
                $modulesAPI[$moduleName] = ['name' => $moduleName, 'actions' => []];
            }

            $methods = [];
            foreach ($currentMethods as $currentMethod)
            {
                $methods[$currentMethod] = true;
            }
            if (count($pathParts) > 0) {
                $actions = \App\datatraffic\lib\Util::rutaAcciones($modulesAPI[$moduleName]['actions'], $pathParts, $methods);
                $modulesAPI[$moduleName]['actions'] = $actions;
            }
            else {
                $modulesAPI[$moduleName]['actions'] = array_merge_recursive($modulesAPI[$moduleName]['actions'], $methods);
            }
        }

        $modulesAPI['manageallcompanies'] = [
            "name" => "manageallcompanies",
            "actions" => [
                "GET" => true,
                "POST" => true,
                "PUT" => true,
                "DELETE" => true
            ]
        ];

        $modulesAPI['manageallresource'] = [
            "name" => "manageallresources",
            "actions" => [
                "GET" => true,
                "POST" => true,
                "PUT" => true,
                "DELETE" => true
            ]
        ];

        $modulesAPI['manageallcreated'] = [
            "name" => "manageallcreated",
            "actions" => [
                "GET" => true,
                "POST" => true,
                "PUT" => true,
                "DELETE" => true
            ]
        ];

        $modulesAPI['viewerrors'] = [
            "name" => "viewerrors",
            "actions" => [
                "GET" => true,
                "POST" => true,
                "PUT" => true,
                "DELETE" => true
            ]
        ];

        //Encontrar modulos WEB
        $modulesWEB = [
            "PlanningTracking" => [
                "name"=> "PlanningTracking",
                "actions"=> [
                    "CREATE"=> true,
                    "READ"=> true,
                    "UPDATE"=> true,
                    "DELETE"=> true
                ]
            ],
            "ResourceTracking" => [
                "name"=> "ResourceTracking",
                "actions"=> [
                    "CREATE"=> true,
                    "READ"=> true,
                    "UPDATE"=> true,
                    "DELETE"=> true
                ]
            ],
            "Companies" => [
                "name"=> "Companies",
                "actions"=> [
                    "CREATE"=> true,
                    "READ"=> true,
                    "UPDATE"=> true,
                    "DELETE"=> true
                ]
            ],
            "Resources" => [
                "name"=> "Resources",
                "actions"=> [
                    "CREATE"=> true,
                    "READ"=> true,
                    "UPDATE"=> true,
                    "DELETE"=> true
                ]
            ],
            "Users" => [
                "name"=> "Users",
                "actions"=> [
                    "CREATE"=> true,
                    "READ"=> true,
                    "UPDATE"=> true,
                    "DELETE"=> true
                ]
            ],
            "Forms" => [
                "name"=> "Forms",
                "actions"=> [
                    "CREATE"=> true,
                    "READ"=> true,
                    "UPDATE"=> true,
                    "DELETE"=> true
                ]
            ],
            "Scheduling" => [
                "name"=> "Scheduling",
                "actions"=> [
                    "CREATE"=> true,
                    "READ"=> true,
                    "UPDATE"=> true,
                    "DELETE"=> true
                ]
            ],
            "Registers" => [
                "name"=> "Registers",
                "actions"=> [
                    "CREATE"=> true,
                    "READ"=> true,
                    "UPDATE"=> true,
                    "DELETE"=> true
                ]
            ],
            "login" => [
                "name"=> "login",
                "actions"=> [
                    "POST"=> true
                ]
            ]
        ];

        $modulesMovil = [
            "workflow" => [
                "name"=> "workflow",
                "actions"=> [
                    "GET"=> true,
                    "POST"=> true,
                    "PUT"=> true,
                    "DELETE"=> true
                ]
            ],
            "tasks" => [
                "name"=> "tasks",
                "actions"=> [
                    "GET"=> true,
                    "POST"=> true,
                    "PUT"=> true,
                    "DELETE"=> true
                ]
            ],
            "registers" => [
                "name"=> "registers",
                "actions"=> [
                    "GET"=> true,
                    "POST"=> true,
                    "PUT"=> true,
                    "DELETE"=> true
                ]
            ],
            "login" => [
                "name"=> "login",
                "actions"=> [
                    "POST"=> true
                ]
            ]
        ];

        $applications = [
            'API' => $modulesAPI,
            "WEB" => $modulesWEB,
            "com.datatraffic.formulariodinamico.app" => $modulesMovil,
        ];

        //Roles de cada aplicacion
        $roles =[
            [
                "name" => "Administrator Datatraffic",
                "application" =>"WEB",
                "modules" => ["PlanningTracking" => [], "ResourceTracking" => [], "Companies" => [], "Resources" => [], "Users" => [], "Forms" => [], "Scheduling" => [], "Registers"  => [], "login" => [],],
                "api" => [
                    "login" => [], "logout" => [], "resourceinstances" => [], "batch" => [], "applications" => [],
                    "roles" => [], "companies" => [], "devicedefinitions" => [], "deviceinstances" => [], "resourcetemplates" => [], "resourcedefinitions" => [],
                    "actualresourceinstance" => [], "actualresource" => [], "tracking" => [], "tasks" => [], "resourcegroups" => [],
                    "geofences" => [], "checkpoints" => [], "speedlimits" => [], "messages" => [], "forms" => [], "sections" => [],
                    "registers" => [], "export" => [], "addresses" => [], "positions" => [], "cities" => [], "countries" => [],
                    "scheduling" => [], 'manageallcompanies' => [], 'manageallresource' => [], 'manageallcreated'=> [], "orders" => [], "routes" => [], "icons" => [], "viewerrors" => [], "eventtypes" => [],
                ],
            ],
            [
                "name" => "Administrador cliente",
                "application" =>"WEB",
                "modules" => ["PlanningTracking" => [], "ResourceTracking" => [], "Resources" => [], "Users" => [], "Forms" => [], "Scheduling" => [], "Registers" => [], "login" => [],],
                "api" => [
                    "login" => [], "logout" => [], "resourceinstances" => [], "batch" => [], "applications" => [],
                    "roles" => [], "companies" => [], "devicedefinitions" => [], "deviceinstances" => [], "resourcetemplates" => [], "resourcedefinitions" => [],
                    "actualresourceinstance" => [], "actualresource" => [], "tracking" => [], "tasks" => [], "resourcegroups" => [],
                    "geofences" => [], "checkpoints" => [], "speedlimits" => [], "messages" => [], "forms" => [], "sections" => [],
                    "registers" => [], "export" => [], "addresses" => [], "positions" => [], "cities" => [], "countries" => [],
                    "scheduling" => [], 'manageallresource' => [], "orders" => [], "routes" => [], "icons" => [], "viewerrors" => [], "eventtypes" => [],
                ],
            ],
            [
                "name" => "Programador y monitoreador",
                "application" =>"WEB",
                "modules" => ["PlanningTracking" => [], "Scheduling" => [], "login" => [],],
                "api" => [
                    "login" => [], "logout" => [], "resourceinstances" => [],
                    "tracking" => [], "tasks" => [], "resourcegroups" => [],
                    "forms" => [], "sections" => [], "addresses" => [], 
                    "scheduling" => [], 'manageallresource' => [], "orders" => [], "routes" => [], "viewerrors" => [], "eventtypes" => [],
                ],
            ],
            [
                "name" => "Administrator Datatraffic",
                "application" => "com.datatraffic.formulariodinamico.app",
                "modules" => ["login" => [], "workflow" => [], "tasks" => [], "registers" => []],
                "api" => [
                    "login" => [], "logout" => [], "batch" => [], "tasks" => [], "messages" => [], "forms" => [],
                    "registers" => [], "positions" => [], "tracking" => [], "orders" => [],
                ],
            ],
            [
                "name" => "Administrador cliente",
                "application" => "com.datatraffic.formulariodinamico.app",
                "modules" => ["login" => [], "workflow" => [], "tasks" => [], "registers" => []],
                "api" => [
                    "login" => [], "logout" => [], "batch" => [], "tasks" => [], "messages" => [], "forms" => [],
                    "registers" => [], "positions" => [], "tracking" => [], "orders" => [],
                ],
            ],
            [
                "name" => "Usuario movil Datatraffic",
                "application" => "com.datatraffic.formulariodinamico.app",
                "modules" => ["login" => [], "workflow" => [], "tasks" => [], "registers" => []],
                "api" => [
                    "login" => [], "logout" => [], "batch" => [], "tasks" => [], "messages" => [], "forms" => [],
                    "registers" => [], "positions" => [], "tracking" => [], "orders" => [],
                ],
            ],
            [
                "name" => "Usuario movil cliente",
                "application" => "com.datatraffic.formulariodinamico.app",
                "modules" => ["login" => [], "workflow" => [], "tasks" => [], "registers" => []],
                "api" => [
                    "login" => [], "logout" => [], "batch" => [], "tasks" => [], "messages" => [], "forms" => [],
                    "registers" => [], "positions" => [], "tracking" => [], "orders" => [],
                ],
            ],
        ];

        //Actualizar aplicaciones
        foreach ($applications as $applicationName => $applicationModules) {
            $application = \App\Models\Applications\Application::where('name','=',$applicationName)->first();
            if($application) {
                $application->modules = array_values($applications[$applicationName]);
                $application->save();
            }
        }
        
        //Actualizar roles
        foreach ($roles as $applicationRoles) {
            $applicationName = $applicationRoles['application'];
            $application = \App\Models\Applications\Application::where('name','=',$applicationName)->first();
            $applicationModules = $applications[$applicationName];

            $roleName = $applicationRoles['name'];
            $role = \App\Models\Users\Role::where('name','=',$roleName)->where('application.name','=',$applicationName)->first();

            if(!$role) {
                $role = new \App\Models\Users\Role();
                $role->name = $roleName;
                $role->save();
            }

            $roleModules = $applicationRoles['modules'];
            $roleApplication = new \App\Models\Applications\Application();
            $roleApplication->_id = $application->_id;
            $roleApplication->name = $application->name;
            $arrayIntersection = array_intersect_key($applicationModules, $roleModules);
            $arrayValues = array_values($arrayIntersection);
            $roleApplication->modules = $arrayValues;
            $role->application()->save($roleApplication);

            //Guardar permisos par el api
            $applicationModules = $applications['API'];
            $apiModules = $applicationRoles['api'];
            $arrayIntersection = array_intersect_key($applicationModules, $apiModules);
            $arrayValues = array_values($arrayIntersection);
            $role->api = ['modules' => $arrayValues];
            $role->save();
        }

        //Actualizar licencias
        $companies = \App\Models\Companies\Company::all();
        foreach ($companies as $company) {
            $company->licenses = [];
            $company->save();

            foreach ($applications as $applicationName => $applicationModules) {
                $license = new \App\Models\Companies\License();
                $application = \App\Models\Applications\Application::where('name','=',$applicationName)->first();
                if($application) {
                    $applicationCopy = $application->replicate();
                    $applicationCopy->_id = $application->_id;
                    $applicationArray = $applicationCopy->toArray();
                    $license->application = $applicationArray;
                    $licenseArray = $license->toArray();
                    $licenseArray['application']['_id'] = new \MongoDB\BSON\ObjectID($application->_id);
                    $licenseArray['_id'] = new \MongoDB\BSON\ObjectID($license->_id);
                    DB::collection('companies')->where('_id', new \MongoDB\BSON\ObjectID($company->_id))->push("licenses", $licenseArray);
                }
            }
        }
    }
}
