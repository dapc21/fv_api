<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

class ControllerTraitUpdateTest extends TestCase{
    
    use DatabaseMigrations;
    
    public function testUpdateRelationOneToOneUpdate()
    {   
        //Peticion
        $method = 'PUT';
        $uri = '/testvehiculos/864172';
      
        $parameters = [
            'data' => 
                    '{
                        "basicinfo": {
                            "id_empresa": 864036,
                            "capacidad_combustible": 100
                        }
                    }',
            'synchronize' => '[]' 
        ];        
        $cookies = [];
        $files = [];
        $server = [];
        $content = null;
        
        $request = Request::create(
            $uri, $method, $parameters,
            $cookies, $files, $server, $content
        );       
        
        //Session
        $this->app['session']->start();
        $token = $this->app['session']->token();
        $request->headers->add(['X-CSRF-TOKEN'=>$token]);
        
        //Hacer peticion
        $response = $this->app->make('Illuminate\Contracts\Http\Kernel')->handle($request);
        
        //Content
        $obj = json_decode($response->getContent());
        
        //Assets
        $this->assertFalse($obj->error);
        $this->assertEquals("OK", $obj->msg);
        
        $vehiculo = \App\Models\Test\Vehiculo::find($obj->data[0]->reference);
        $this->assertEquals("SSZ570", $vehiculo->placa);
        $this->assertEquals(864036, $vehiculo->id_empresa);
        $this->assertEquals(100, $vehiculo->capacidad_combustible);        
    }
         
    public function testUpdateRelationOneToOneInsert()
    {   
        //Peticion
        $method = 'PUT';
        $uri = '/testvehiculos/864172';
      
        $parameters = [
            'data' => 
                    '{
                      "basicinfo": {
                        "capacidad_combustible": 100
                      },
                      "empresa": [
                        {
                            "basicinfo": {
                              "nombre": "Datatraffic SAS"
                            }
                        }
                      ]
                    }',
            'synchronize' => '[]' 
        ];        
        $cookies = [];
        $files = [];
        $server = [];
        $content = null;
        
        $request = Request::create(
            $uri, $method, $parameters,
            $cookies, $files, $server, $content
        );       
        
        //Session
        $this->app['session']->start();
        $token = $this->app['session']->token();
        $request->headers->add(['X-CSRF-TOKEN'=>$token]);
        
        //Hacer peticion
        $response = $this->app->make('Illuminate\Contracts\Http\Kernel')->handle($request);
        
        //Content
        $obj = json_decode($response->getContent());
        
        //Assets
        $this->assertFalse($obj->error);
        $this->assertEquals("OK", $obj->msg);
        
        $vehiculo = \App\Models\Test\Vehiculo::find($obj->data[0]->reference);
        $this->assertEquals("SSZ570", $vehiculo->placa);
        $this->assertEquals(100, $vehiculo->capacidad_combustible);   
        
        $empresa = \App\Models\Test\Empresa::where("nombre","=","Datatraffic SAS")->first();        
        $this->assertEquals($empresa->id_empresa, $vehiculo->id_empresa);     
    }    
    
    public function testUpdateRelationOneToManyInsert()
    {   
        //Peticion
        $method = 'PUT';
        $uri = '/testvehiculos/864172';
      
        $parameters = [
            'data' => 
                    '{
                      "basicinfo": {
                        "capacidad_combustible": 100
                      },
                      "dispositivos": [
                        {
                            "basicinfo": {
                              "imei": "357666051211669"
                            }
                        }
                      ]
                    }',
            'synchronize' => '[]' 
        ];        
        $cookies = [];
        $files = [];
        $server = [];
        $content = null;
        
        $request = Request::create(
            $uri, $method, $parameters,
            $cookies, $files, $server, $content
        );       
        
        //Session
        $this->app['session']->start();
        $token = $this->app['session']->token();
        $request->headers->add(['X-CSRF-TOKEN'=>$token]);
        
        //Hacer peticion
        $response = $this->app->make('Illuminate\Contracts\Http\Kernel')->handle($request);
        
        //Content
        $obj = json_decode($response->getContent());
        
        //Assets
        $this->assertFalse($obj->error);
        $this->assertEquals("OK", $obj->msg);
        
        $vehiculo = \App\Models\Test\Vehiculo::find($obj->data[0]->reference);
        $this->assertEquals("SSZ570", $vehiculo->placa);
        $this->assertEquals(864031, $vehiculo->id_empresa);
        $this->assertEquals(100, $vehiculo->capacidad_combustible);         
        
        $dispositivo = \App\Models\Test\Dispositivo::where("imei","=","357666051211669")->first();        
        $this->assertEquals($vehiculo->id_vehiculo, $dispositivo->id_vehiculo);
    }
    
    public function testUpdateRelationOneToManyUpdate()
    {   
        //Peticion
        $method = 'PUT';
        $uri = '/testvehiculos/864172';
      
        $parameters = [
            'data' => 
                    '{
                      "basicinfo": {
                        "capacidad_combustible": 100
                      },
                      "dispositivos": [
                        {
                            "__id" : 564149
                        },
                        {
                            "basicinfo": {
                                  "imei": "357666051211669"
                            }
                        }
                      ]
                    }',
            'synchronize' => '[]' 
        ];
        $cookies = [];
        $files = [];
        $server = [];
        $content = null;
        
        $request = Request::create(
            $uri, $method, $parameters,
            $cookies, $files, $server, $content
        );       
        
        //Session
        $this->app['session']->start();
        $token = $this->app['session']->token();
        $request->headers->add(['X-CSRF-TOKEN'=>$token]);
        
        //Hacer peticion
        $response = $this->app->make('Illuminate\Contracts\Http\Kernel')->handle($request);
        
        //Content
        $obj = json_decode($response->getContent());
        
        //Assets
        $this->assertFalse($obj->error);
        $this->assertEquals("OK", $obj->msg);
        
        $vehiculo = \App\Models\Test\Vehiculo::find($obj->data[0]->reference);
        $this->assertEquals("SSZ570", $vehiculo->placa);
        $this->assertEquals(864031, $vehiculo->id_empresa);
        $this->assertEquals(100, $vehiculo->capacidad_combustible);         
        
        $dispositivo = \App\Models\Test\Dispositivo::find(564149);        
        $this->assertEquals($vehiculo->id_vehiculo, $dispositivo->id_vehiculo);         
    }    
    
    public function testUpdateRelationManyToManyInsert()
    {   
        //Peticion
        $method = 'PUT';
        $uri = '/testvehiculos/864172';
      
        $parameters = [
            'data' => 
                    '{
                      "basicinfo": {
                        "capacidad_combustible": 100
                      },
                      "conductores": [
                        {
                            "basicinfo":
                            {
                                "nombres" : "SERGIO EDUARDO",
                                "apellidos" : "SINUCO LEON",
                                "num_documento" : "1014193341",
                                "celular" : "3108842650",
                                "id_empresa": 864031
                            },
                            "__pivot":
                            {
                               "dias":"MIJVSD",
                               "hora_inicio":"08:00",
                               "hora_fin":"17:00"
                            }                            
                        },
                        {
                            "basicinfo":
                            {
                                "nombres" : "NESTOR ORLANDO",
                                "apellidos" : "ROJAS PENARANDA",
                                "num_documento" : "56487100",
                                "celular" : "3177917103",
                                "id_empresa": 864031
                            }                        
                        }
                      ]
                    }',
            'synchronize' => '[]' 
        ];        
        $cookies = [];
        $files = [];
        $server = [];
        $content = null;
        
        $request = Request::create(
            $uri, $method, $parameters,
            $cookies, $files, $server, $content
        );       
        
        //Session
        $this->app['session']->start();
        $token = $this->app['session']->token();
        $request->headers->add(['X-CSRF-TOKEN'=>$token]);
        
        //Hacer peticion
        $response = $this->app->make('Illuminate\Contracts\Http\Kernel')->handle($request);
        
        //Content
        $obj = json_decode($response->getContent());
        
        //Assets
        $this->assertFalse($obj->error);
        $this->assertEquals("OK", $obj->msg);
        
        $vehiculo = \App\Models\Test\Vehiculo::find($obj->data[0]->reference);
        $this->assertEquals("SSZ570", $vehiculo->placa);
        $this->assertEquals(864031, $vehiculo->id_empresa);
        $this->assertEquals(100, $vehiculo->capacidad_combustible);         
        
        $conductores = $vehiculo->conductores;
        
        $conductor = $conductores->first();
        $this->assertEquals("SERGIO EDUARDO",$conductor->nombres);
        $this->assertEquals("SINUCO LEON",$conductor->apellidos);
        $this->assertEquals("1014193341",$conductor->num_documento);
        $this->assertEquals("3108842650",$conductor->celular);
        $this->assertEquals(864031,$conductor->id_empresa);  
        $this->assertEquals("MIJVSD",$conductor->pivot->dias);  
        $this->assertEquals("08:00",$conductor->pivot->hora_inicio);  
        $this->assertEquals("17:00",$conductor->pivot->hora_fin);
        
        $conductor = $conductores->last();
        $this->assertEquals("NESTOR ORLANDO",$conductor->nombres);
        $this->assertEquals("ROJAS PENARANDA",$conductor->apellidos);
        $this->assertEquals("56487100",$conductor->num_documento);
        $this->assertEquals("3177917103",$conductor->celular);
        $this->assertEquals(864031,$conductor->id_empresa);
        $this->assertNull($conductor->pivot->dias);  
        $this->assertNull($conductor->pivot->hora_inicio);  
        $this->assertNull($conductor->pivot->hora_fin);
    }
    
    public function testUpdateRelationManyToManyInsertRollback()
    {   
        //Fijar el id de la tabla test_conductor para que sea 864159 y falle las dos inserciones
        DB::statement("SELECT setval('public.test_conductor_id_conductor_seq', 864159, true)");
        
        //Peticion
        $method = 'PUT';
        $uri = '/testvehiculos/864172';
      
        $parameters = [
            'data' => 
                    '{
                      "basicinfo": {
                        "capacidad_combustible": 100
                      },
                      "conductores": [
                        {
                            "basicinfo":
                            {
                                "nombres" : "SERGIO EDUARDO",
                                "apellidos" : "SINUCO LEON",
                                "num_documento" : "1014193341",
                                "celular" : "3108842650",
                                "id_empresa": 864031
                            },
                            "__pivot":
                            {
                               "dias":"MIJVSD",
                               "hora_inicio":"08:00",
                               "hora_fin":"17:00"
                            }                            
                        },
                        {
                            "basicinfo":
                            {
                                "nombres" : "NESTOR ORLANDO",
                                "apellidos" : "ROJAS PENARANDA",
                                "num_documento" : "56487100",
                                "celular" : "3177917103",
                                "id_empresa": 864031
                            }                        
                        }
                      ]
                    }',
            'synchronize' => '[]' 
        ];        
        $cookies = [];
        $files = [];
        $server = [];
        $content = null;
        
        $request = Request::create(
            $uri, $method, $parameters,
            $cookies, $files, $server, $content
        );       
        
        //Session
        $this->app['session']->start();
        $token = $this->app['session']->token();
        $request->headers->add(['X-CSRF-TOKEN'=>$token]);
        
        //Hacer peticion
        $response = $this->app->make('Illuminate\Contracts\Http\Kernel')->handle($request);
        
        //Content
        $obj = json_decode($response->getContent());
        
        //Assets
        $this->assertTrue($obj->error);
        
        $vehiculo = \App\Models\Test\Vehiculo::find(864172);
        $this->assertEquals(203, $vehiculo->capacidad_combustible);
        
        $conductor = \App\Models\Test\Conductor::where('num_documento','=','1014193341')->first();
        $this->assertNull($conductor);
        $conductor = \App\Models\Test\Conductor::where('num_documento','=','56487100')->first();
        $this->assertNull($conductor);          
    }    
    
    public function testUpdateRelationManyToManyInsertRollback2()
    {   
        //Fijar el id de la tabla test_conductor para que sea 864158 y falle solo una insercion
        DB::statement("SELECT setval('public.test_conductor_id_conductor_seq', 864158, true)");
        
        //Peticion
        $method = 'PUT';
        $uri = '/testvehiculos/864172';
      
        $parameters = [
            'data' => 
                    '{
                      "basicinfo": {
                        "capacidad_combustible": 100
                      },
                      "conductores": [
                        {
                            "basicinfo":
                            {
                                "nombres" : "SERGIO EDUARDO",
                                "apellidos" : "SINUCO LEON",
                                "num_documento" : "1014193341",
                                "celular" : "3108842650",
                                "id_empresa": 864031
                            },
                            "__pivot":
                            {
                               "dias":"MIJVSD",
                               "hora_inicio":"08:00",
                               "hora_fin":"17:00"
                            }                            
                        },
                        {
                            "basicinfo":
                            {
                                "nombres" : "NESTOR ORLANDO",
                                "apellidos" : "ROJAS PENARANDA",
                                "num_documento" : "56487100",
                                "celular" : "3177917103",
                                "id_empresa": 864031
                            }                        
                        }
                      ]
                    }',
            'synchronize' => '[]' 
        ];        
        $cookies = [];
        $files = [];
        $server = [];
        $content = null;
        
        $request = Request::create(
            $uri, $method, $parameters,
            $cookies, $files, $server, $content
        );       
        
        //Session
        $this->app['session']->start();
        $token = $this->app['session']->token();
        $request->headers->add(['X-CSRF-TOKEN'=>$token]);
        
        //Hacer peticion
        $response = $this->app->make('Illuminate\Contracts\Http\Kernel')->handle($request);
        
        //Content
        $obj = json_decode($response->getContent());
        
        //Assets
        $this->assertTrue($obj->error);
        
        $vehiculo = \App\Models\Test\Vehiculo::find(864172);
        $this->assertEquals(203, $vehiculo->capacidad_combustible);
        
        $conductor = \App\Models\Test\Conductor::where('num_documento','=','1014193341')->first();
        $this->assertNull($conductor);
        $conductor = \App\Models\Test\Conductor::where('num_documento','=','56487100')->first();
        $this->assertNull($conductor);          
    }
    
    public function testUpdateRelationManyToManyInsertRollback3()
    {   
        //Fijar el id de la tabla test_conductor_vehiculo para que sea 764159 y falle las dos inserciones
        DB::statement("SELECT setval('public.test_conductor_vehiculo_id_conductor_vehiculo_seq', 764159, true)");
        
        //Peticion
        $method = 'PUT';
        $uri = '/testvehiculos/864172';
      
        $parameters = [
            'data' => 
                    '{
                      "basicinfo": {
                        "capacidad_combustible": 100
                      },
                      "conductores": [
                        {
                            "basicinfo":
                            {
                                "nombres" : "SERGIO EDUARDO",
                                "apellidos" : "SINUCO LEON",
                                "num_documento" : "1014193341",
                                "celular" : "3108842650",
                                "id_empresa": 864031
                            },
                            "__pivot":
                            {
                               "dias":"MIJVSD",
                               "hora_inicio":"08:00",
                               "hora_fin":"17:00"
                            }                            
                        },
                        {
                            "basicinfo":
                            {
                                "nombres" : "NESTOR ORLANDO",
                                "apellidos" : "ROJAS PENARANDA",
                                "num_documento" : "56487100",
                                "celular" : "3177917103",
                                "id_empresa": 864031
                            }                        
                        }
                      ]
                    }',
            'synchronize' => '[]' 
        ];        
        $cookies = [];
        $files = [];
        $server = [];
        $content = null;
        
        $request = Request::create(
            $uri, $method, $parameters,
            $cookies, $files, $server, $content
        );       
        
        //Session
        $this->app['session']->start();
        $token = $this->app['session']->token();
        $request->headers->add(['X-CSRF-TOKEN'=>$token]);
        
        //Hacer peticion
        $response = $this->app->make('Illuminate\Contracts\Http\Kernel')->handle($request);
        
        //Content
        $obj = json_decode($response->getContent());
        
        //Assets
        $this->assertTrue($obj->error);
        
        $vehiculo = \App\Models\Test\Vehiculo::find(864172);
        $this->assertEquals(203, $vehiculo->capacidad_combustible);
        
        $conductor = \App\Models\Test\Conductor::where('num_documento','=','1014193341')->first();
        $this->assertNull($conductor);
        $conductor = \App\Models\Test\Conductor::where('num_documento','=','56487100')->first();
        $this->assertNull($conductor);          
    }    
    
    public function testUpdateRelationManyToManyUpdate()
    {   
        //Peticion
        $method = 'PUT';
        $uri = '/testvehiculos/864172';
      
        $parameters = [
            'data' => 
                    '{
                      "basicinfo": {
                        "capacidad_combustible": 100,
                        "id_empresa": 864036
                      },
                      "conductores": [
                        {
                            "__id" : 864164,
                            "__pivot":
                                {
                                        "dias":"MIJVSD",
                                        "hora_inicio":"08:00",
                                        "hora_fin":"17:00"
                                }
                        }
                        ,
                        {
                            "__id" : 864165
                        }
                      ]
                    }',
            'synchronize' => '[]' 
        ];        
        $cookies = [];
        $files = [];
        $server = [];
        $content = null;//borra el resto el 
        
        $request = Request::create(
            $uri, $method, $parameters,
            $cookies, $files, $server, $content
        );       
        
        //Session
        $this->app['session']->start();
        $token = $this->app['session']->token();
        $request->headers->add(['X-CSRF-TOKEN'=>$token]);
        
        //Hacer peticion
        $response = $this->app->make('Illuminate\Contracts\Http\Kernel')->handle($request);
        
        //Content
        $obj = json_decode($response->getContent());
        
        //Assets
        $this->assertFalse($obj->error);
        $this->assertEquals("OK", $obj->msg);
        
        $vehiculo = \App\Models\Test\Vehiculo::find($obj->data[0]->reference);
        $this->assertEquals("SSZ570", $vehiculo->placa);
        $this->assertEquals(100, $vehiculo->capacidad_combustible);
        $this->assertEquals(864036, $vehiculo->id_empresa);
        
        $conductor = \App\Models\Test\Conductor::find(864164)->vehiculos()->where('test_conductor_vehiculo.id_vehiculo','=',$obj->data[0]->reference)->first();
        $this->assertEquals($vehiculo->id_vehiculo,$conductor->id_vehiculo);
        
        $conductor = \App\Models\Test\Conductor::find(864165)->vehiculos()->where('test_conductor_vehiculo.id_vehiculo','=',$obj->data[0]->reference)->first();
        $this->assertEquals($vehiculo->id_vehiculo,$conductor->id_vehiculo);
    }    
}
