<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

class ControllerTraitStoreTest extends TestCase{
    
    use DatabaseMigrations;
    
    public function testStoreRelationOneToOneUpdate()
    {   
        \Log::error("testStoreRelationOneToOneUpdate");
        //Peticion
        $method = 'POST';
        $uri = '/testvehiculos';
      
        $parameters = [
            'data' => 
                    '{
                        "basicinfo":{
                           "placa":"HOA521",
                           "id_empresa":864041,
                           "capacidad_combustible":135
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
        $obj = json_decode($response->getContent() );//{"error":false,"msg":"OK","total":0,"pagination":{"reference":1},
        
        \Log::info("testStoreRelationOneToOneUpdate paso ".$response->getContent());
        //Assets
        $this->assertFalse($obj->error);
        $this->assertEquals("OK", $obj->msg);
        
        $vehiculo = \App\Models\Test\Vehiculo::find( $obj->data[0]->reference );
        $this->assertEquals("HOA521", $vehiculo->placa);
        $this->assertEquals(864041, $vehiculo->id_empresa);
        $this->assertEquals(135, $vehiculo->capacidad_combustible);
        
    }
         
    public function testStoreRelationOneToOneInsert()
    {   
        //Peticion
        $method = 'POST';
        $uri = '/testvehiculos';
      
        $parameters = [
            'data' => 
                    '{  
                        "basicinfo":{  
                           "placa":"HOA521",
                           "capacidad_combustible":135
                        },
                        "empresa":[  
                           {  
                              "basicinfo":{  
                                 "nombre":"Datatraffic SAS"
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
        $this->assertEquals("HOA521", $vehiculo->placa);
        $this->assertEquals(135, $vehiculo->capacidad_combustible);       
        
        $empresa = \App\Models\Test\Empresa::where("nombre","=","Datatraffic SAS")->first();  
        
        
         \Log::info("Test 2 ".$vehiculo);
         \Log::info("Test 2 ".$empresa);
         
        $this->assertEquals($empresa->id_empresa, $vehiculo->id_empresa);        
    }    
    
    public function testStoreRelationOneToManyInsert()
    {   
        //Peticion
        $method = 'POST';
        $uri = '/testvehiculos';
      
        $parameters = [
            'data' => 
                    '{
                        "basicinfo":{
                           "placa":"HOA521",
                           "id_empresa":864041,
                           "capacidad_combustible":135
                        },
                        "dispositivos":[
                           {
                              "basicinfo":{
                                 "imei":"357666051211669"
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
        $this->assertEquals("HOA521", $vehiculo->placa);
        $this->assertEquals(864041, $vehiculo->id_empresa);
        $this->assertEquals(135, $vehiculo->capacidad_combustible);         
        
        $dispositivo = \App\Models\Test\Dispositivo::where("imei","=","357666051211669")->first();      
        
        \Log::info("METODO 3 ".$vehiculo);
        \Log::info("METODO 3 ".$dispositivo);
        $this->assertEquals($vehiculo->id_vehiculo, $dispositivo->id_vehiculo);
    }
    
    public function testStoreRelationOneToManyUpdate()
    {   
        //Peticion
        $method = 'POST';
        $uri = '/testvehiculos';
      
        $parameters = [
            'data' => '{
                            "basicinfo":{
                               "placa":"HOA521",
                               "id_empresa":864041,
                               "capacidad_combustible":135
                            },
                            "dispositivos":[
                               {
                                  "__id":564149
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
        $this->assertEquals("HOA521", $vehiculo->placa);
        $this->assertEquals(864041, $vehiculo->id_empresa);
        $this->assertEquals(135, $vehiculo->capacidad_combustible);         
        
        $dispositivo = \App\Models\Test\Dispositivo::find(564149);        
        $this->assertEquals($vehiculo->id_vehiculo, $dispositivo->id_vehiculo);        
    }
    
    public function testStoreRelationManyToManyInsert()
    {   
        //Peticion
        $method = 'POST';
        $uri = '/testvehiculos';
      
        $parameters = [
            'data' => '{
                        "basicinfo":{
                           "placa":"HOA521",
                           "capacidad_combustible":135,
                           "id_empresa":864041
                        },
                        "conductores":[
                           {
                              "basicinfo":{
                                 "nombres":"SERGIO EDUARDO",
                                 "apellidos":"SINUCO LEON",
                                 "num_documento":"1014193341",
                                 "celular":"3108842650",
                                 "id_empresa":864041
                              },
                              "__pivot":{
                                 "dias":"MIJVSD",
                                 "hora_inicio":"08:00",
                                 "hora_fin":"17:00"
                              }
                           },
                           {
                              "basicinfo":{
                                 "nombres":"NESTOR ORLANDO",
                                 "apellidos":"ROJAS PENARANDA",
                                 "num_documento":"56487100",
                                 "celular":"3177917103",
                                 "id_empresa":864041
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
        $this->assertEquals("HOA521", $vehiculo->placa);
        $this->assertEquals(864041, $vehiculo->id_empresa);
        $this->assertEquals(135, $vehiculo->capacidad_combustible);         
        
        $conductores = \App\Models\Test\Conductor::whereHas('vehiculos', function ($query) use ($obj) {
                            $query->where("test_conductor_vehiculo.id_vehiculo","=",$obj->data[0]->reference);
                        })->orderBy('id_conductor')->get();
        
        $conductor = $conductores->first();
        $this->assertEquals("SERGIO EDUARDO",$conductor->nombres);
        $this->assertEquals("SINUCO LEON",$conductor->apellidos);
        $this->assertEquals("1014193341",$conductor->num_documento);
        $this->assertEquals("3108842650",$conductor->celular);
        $this->assertEquals(864041,$conductor->id_empresa);
        $this->assertCount(1, $conductor->vehiculos);
        
        foreach ($conductor->vehiculos as $vehiculo)
        {
            $this->assertEquals("MIJVSD",$vehiculo->pivot->dias);  
            $this->assertEquals("08:00",$vehiculo->pivot->hora_inicio);  
            $this->assertEquals("17:00",$vehiculo->pivot->hora_fin);
        }
    
        $conductor = $conductores->last();
        $this->assertEquals("NESTOR ORLANDO",$conductor->nombres);
        $this->assertEquals("ROJAS PENARANDA",$conductor->apellidos);
        $this->assertEquals("56487100",$conductor->num_documento);
        $this->assertEquals("3177917103",$conductor->celular);
        $this->assertEquals(864041,$conductor->id_empresa);   
        
        $this->assertCount(1, $conductor->vehiculos);
        
        foreach ($conductor->vehiculos as $vehiculo)
        {
            $this->assertNull($vehiculo->pivot->dias);  
            $this->assertNull($vehiculo->pivot->hora_inicio);  
            $this->assertNull($vehiculo->pivot->hora_fin); 
        }
    }
    
    public function testStoreRelationManyToManyInsertRollback()
    {
        //Fijar el id de la tabla test_conductor para que sea 864159 y falle las dos inserciones
        DB::statement("SELECT setval('public.test_conductor_id_conductor_seq', 864159, true)");
        
        //Peticion
        $method = 'POST';
        $uri = '/testvehiculos';
      
        $parameters = [
            'data' => '{
                        "basicinfo":{
                           "placa":"HOA521",
                           "capacidad_combustible":135,
                           "id_empresa":864041
                        },
                        "conductores":[
                           {
                              "basicinfo":{
                                 "nombres":"SERGIO EDUARDO",
                                 "apellidos":"SINUCO LEON",
                                 "num_documento":"1014193341",
                                 "celular":"3108842650",
                                 "id_empresa":864041
                              },
                              "__pivot":{
                                 "dias":"MIJVSD",
                                 "hora_inicio":"08:00",
                                 "hora_fin":"17:00"
                              }
                           },
                           {
                              "basicinfo":{
                                 "nombres":"NESTOR ORLANDO",
                                 "apellidos":"ROJAS PENARANDA",
                                 "num_documento":"56487100",
                                 "celular":"3177917103",
                                 "id_empresa":864041
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
        $vehiculo = \App\Models\Test\Vehiculo::where('placa','=','HOA521')->first();
        $this->assertNull($vehiculo);
        $conductor = \App\Models\Test\Conductor::where('num_documento','=','1014193341')->first();
        $this->assertNull($conductor);
        $conductor = \App\Models\Test\Conductor::where('num_documento','=','56487100')->first();
        $this->assertNull($conductor);        
    }
    
    public function testStoreRelationManyToManyInsertRollback2()
    {
        //Fijar el id de la tabla test_conductor para que sea 864158 y falle solo una insercion
        DB::statement("SELECT setval('public.test_conductor_id_conductor_seq', 864158, true)");
        
        //Peticion
        $method = 'POST';
        $uri = '/testvehiculos';
      
        $parameters = [
            'data' => '{
                        "basicinfo":{
                           "placa":"HOA521",
                           "capacidad_combustible":135,
                           "id_empresa":864041
                        },
                        "conductores":[
                           {
                              "basicinfo":{
                                 "nombres":"SERGIO EDUARDO",
                                 "apellidos":"SINUCO LEON",
                                 "num_documento":"1014193341",
                                 "celular":"3108842650",
                                 "id_empresa":864041
                              },
                              "__pivot":{
                                 "dias":"MIJVSD",
                                 "hora_inicio":"08:00",
                                 "hora_fin":"17:00"
                              }
                           },
                           {
                              "basicinfo":{
                                 "nombres":"NESTOR ORLANDO",
                                 "apellidos":"ROJAS PENARANDA",
                                 "num_documento":"56487100",
                                 "celular":"3177917103",
                                 "id_empresa":864041
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
        $vehiculo = \App\Models\Test\Vehiculo::where('placa','=','HOA521')->first();
        $this->assertNull($vehiculo);
        $conductor = \App\Models\Test\Conductor::where('num_documento','=','1014193341')->first();
        $this->assertNull($conductor);
        $conductor = \App\Models\Test\Conductor::where('num_documento','=','56487100')->first();
        $this->assertNull($conductor);         
    }    
    
    public function testStoreRelationManyToManyInsertRollback3()
    {
        //Fijar el id de la tabla test_conductor_vehiculo para que sea 764159 y falle las dos inserciones
        DB::statement("SELECT setval('public.test_conductor_vehiculo_id_conductor_vehiculo_seq', 764159, true)");
        
        //Peticion
        $method = 'POST';
        $uri = '/testvehiculos';
      
        $parameters = [
            'data' => '{
                        "basicinfo":{
                           "placa":"HOA521",
                           "capacidad_combustible":135,
                           "id_empresa":864041
                        },
                        "conductores":[
                           {
                              "basicinfo":{
                                 "nombres":"SERGIO EDUARDO",
                                 "apellidos":"SINUCO LEON",
                                 "num_documento":"1014193341",
                                 "celular":"3108842650",
                                 "id_empresa":864041
                              },
                              "__pivot":{
                                 "dias":"MIJVSD",
                                 "hora_inicio":"08:00",
                                 "hora_fin":"17:00"
                              }
                           },
                           {
                              "basicinfo":{
                                 "nombres":"NESTOR ORLANDO",
                                 "apellidos":"ROJAS PENARANDA",
                                 "num_documento":"56487100",
                                 "celular":"3177917103",
                                 "id_empresa":864041
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
        $vehiculo = \App\Models\Test\Vehiculo::where('placa','=','HOA521')->first();
        $this->assertNull($vehiculo);
        $conductor = \App\Models\Test\Conductor::where('num_documento','=','1014193341')->first();
        $this->assertNull($conductor);
        $conductor = \App\Models\Test\Conductor::where('num_documento','=','56487100')->first();
        $this->assertNull($conductor);         
    }    
    
    public function testStoreRelationManyToManyUpdate()
    {   
        //Peticion
        $method = 'POST';
        $uri = '/testvehiculos';
      
        $parameters = [
            'data' => 
                '{
                    "basicinfo":{
                       "placa":"HOA521",
                       "capacidad_combustible":135,
                       "id_empresa":864041
                    },
                    "conductores":[
                       {
                          "__id":864164,
                          "__pivot":{
                             "dias":"MIJVSD",
                             "hora_inicio":"08:00",
                             "hora_fin":"17:00"
                          }
                       },
                       {
                          "__id":864165
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
        $this->assertEquals("HOA521", $vehiculo->placa);
        $this->assertEquals(864041, $vehiculo->id_empresa);
        $this->assertEquals(135, $vehiculo->capacidad_combustible);         
        
        $conductores = $vehiculo->conductores;
        $this->assertEquals(2,$conductores->count());
        $this->assertEquals(864164,$conductores->first()->id_conductor);
        $this->assertEquals("MIJVSD",$conductores->first()->pivot->dias);  
        $this->assertEquals("08:00",$conductores->first()->pivot->hora_inicio);  
        $this->assertEquals("17:00",$conductores->first()->pivot->hora_fin);
        
        $this->assertEquals(864165,$conductores->last()->id_conductor);
        $this->assertNull($conductores->last()->pivot->dias);  
        $this->assertNull($conductores->last()->pivot->hora_inicio);  
        $this->assertNull($conductores->last()->pivot->hora_fin);          
    }
}
