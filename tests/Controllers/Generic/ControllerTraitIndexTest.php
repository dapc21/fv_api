<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

class ControllerTraitIndexTest extends TestCase{
    
    use DatabaseMigrations;
    static public $uriTest = "/testvehiculosmongo";


    public function testFirstPage() 
    {   
        //Peticion
        $method = 'GET';
        $uri = self::$uriTest;  
        
        $sort = '[
            {
              "property": "placa",
              "direction": "DESC"
            }
          ]';        
        $page = 1;
        $limit = 10;        
        $parameters = [
            'sort' => $sort,
            'page' => $page,
            'limit' => $limit
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
        $this->assertEquals($obj->pagination->total,172);
        $this->assertEquals($obj->pagination->per_page,$limit);
        $this->assertEquals($obj->pagination->current_page,$page);
        $this->assertCount($limit,$obj->data);
        
        $this->assertEquals("XVW934", $obj->data[0]->placa);
        $this->assertEquals("XVK132", $obj->data[1]->placa);
        $this->assertEquals("XMD834", $obj->data[2]->placa);
        $this->assertEquals("XMD742", $obj->data[3]->placa);
        $this->assertEquals("XMA984", $obj->data[4]->placa);
        $this->assertEquals("XMA983", $obj->data[5]->placa);
        $this->assertEquals("WLQ564", $obj->data[6]->placa);
        $this->assertEquals("WHM830", $obj->data[7]->placa);
        $this->assertEquals("WFV979", $obj->data[8]->placa);
        $this->assertEquals("UYY807", $obj->data[9]->placa);
    }
    
    //Le da prioridad a el caracter _
    public function testSecondPage()
    {   
        //Peticion
        $method = 'GET';
        $uri = self::$uriTest;
        
        $sort = '[
            {
              "property": "placa",
              "direction": "DESC"
            }
          ]';        
        $page = 2;
        $limit = 10;        
        $parameters = [
            'sort' => $sort,
            'page' => $page,
            'limit' => $limit
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
        $this->assertEquals($obj->pagination->total,172);
        $this->assertEquals($obj->pagination->per_page,$limit);
        $this->assertEquals($obj->pagination->current_page,$page);
        $this->assertCount($limit,$obj->data);
        
        $this->assertEquals("UYX494", $obj->data[0]->placa);
        $this->assertEquals("UYX493", $obj->data[1]->placa);
        $this->assertEquals("UYX480", $obj->data[2]->placa);
        $this->assertEquals("UYX450", $obj->data[3]->placa);
        $this->assertEquals("UYX397", $obj->data[4]->placa);
        $this->assertEquals("USC408", $obj->data[5]->placa);
        $this->assertEquals("TTV401", $obj->data[6]->placa);
        $this->assertEquals("TTS498", $obj->data[7]->placa);
        $this->assertEquals("TTS122", $obj->data[8]->placa);
        $this->assertEquals("TTS094", $obj->data[9]->placa);        
    }    
    
    public function testSimpleOrderDESC()
    {   
        //Peticion orden descendente
        $method = 'GET';
        $uri = self::$uriTest;
        
        $sort = '[
            {
              "property": "placa",
              "direction": "DESC"
            }
          ]';        
        $page = 1;
        $limit = 10;        
        $parameters = [
            'sort' => $sort,
            'page' => $page,
            'limit' => $limit
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
        $this->assertEquals("XVW934", $obj->data[0]->placa);
        $this->assertEquals("XVK132", $obj->data[1]->placa);
        $this->assertEquals("XMD834", $obj->data[2]->placa);
        $this->assertEquals("XMD742", $obj->data[3]->placa);
        $this->assertEquals("XMA984", $obj->data[4]->placa);
        $this->assertEquals("XMA983", $obj->data[5]->placa);
        $this->assertEquals("WLQ564", $obj->data[6]->placa);
        $this->assertEquals("WHM830", $obj->data[7]->placa);
        $this->assertEquals("WFV979", $obj->data[8]->placa);
        $this->assertEquals("UYY807", $obj->data[9]->placa);
           
    }    
    
     public function testSimpleOrderASC()
    {   
        //Peticion orden descendente
        $method = 'GET';
        $uri = self::$uriTest;
        
        $sort = '[
            {
              "property": "placa",
              "direction": "ASC"
            }
          ]';        
        $page = 1;
        $limit = 10;        
        $parameters = [
            'sort' => $sort,
            'page' => $page,
            'limit' => $limit
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
        $this->assertEquals("GGN920", $obj->data[0]->placa);
        $this->assertEquals("NNN000", $obj->data[1]->placa);
        $this->assertEquals("PPP111", $obj->data[2]->placa);
        $this->assertEquals("PVJ161", $obj->data[3]->placa);
        $this->assertEquals("QGQ240", $obj->data[4]->placa);
        $this->assertEquals("QGQ518", $obj->data[5]->placa);
        $this->assertEquals("QHH026", $obj->data[6]->placa);
        $this->assertEquals("QHH782", $obj->data[7]->placa);
        $this->assertEquals("QHH783", $obj->data[8]->placa);
        $this->assertEquals("QHI124", $obj->data[9]->placa);
           
    }
    
    //Ordenamiento Relacionado No funciona
    public function testRelationOrder()
    {   
        //Peticion orden descendente
        $method = 'GET';
        $uri = self::$uriTest;
        
        $sort = '[
            {
              "property": "empresa.nombre",
              "direction": "ASC"
            },
            {
              "property": "placa",
              "direction": "ASC"
            }            
          ]';        
        $page = 1;
        $limit = 10;        
        $parameters = [
            'sort' => $sort,
            'page' => $page,
            'limit' => $limit
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
        $this->assertEquals("SSZ827", $obj->data[0]->placa);
        $this->assertEquals("SKF916", $obj->data[1]->placa);
        $this->assertEquals("SSZ545", $obj->data[2]->placa);
        $this->assertEquals("STZ785", $obj->data[3]->placa);
        $this->assertEquals("SUE926", $obj->data[4]->placa);
        $this->assertEquals("THR034", $obj->data[5]->placa);
        $this->assertEquals("TSX391", $obj->data[6]->placa);        
        $this->assertEquals("SMG845", $obj->data[7]->placa);
        $this->assertEquals("SSZ808", $obj->data[8]->placa);
        $this->assertEquals("STA008", $obj->data[9]->placa);
           
    }
    
    public function testFilterEq()
    {
        //eq, lt, gt, lte, gte, btw,  dif, isnotnull, isnull
                
        //Peticion orden descendente
        $method = 'GET';
        $uri = self::$uriTest;
        
        $filters ='{
                        "and":
                        [
                            {
                            "field": "capacidad_combustible",
                            "comparison": "eq",
                            "value": 100
                            }
                        ]
                    }';        
        $sort = '[
            {
              "property": "placa",
              "direction": "ASC"
            }
          ]';        
        $page = 1;
        $limit = 10;  
        $relations = '[
            "empresa"
          ]';  
        $parameters = [
            'filters' => $filters,
            'sort' => $sort,
            'page' => $page,
            'limit' => $limit,
            'relations'=>$relations
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
        $this->assertEquals("SSZ819", $obj->data[0]->placa);       
    }
    
    public function testFilterLtGt()
    {
        //eq, lt, gt, lte, gte, btw,  dif, isnotnull, isnull
                
        //Peticion orden descendente
        $method = 'GET';
        $uri = self::$uriTest;
        
        $filters =  '{
                        "and":[
                            {
                                "field": "capacidad_combustible",
                                "comparison": "gt",
                                "value": 120
                            },
                            {
                                "field": "capacidad_combustible",
                                "comparison": "lt",
                                "value": 140
                            }            
                        ]
                    }';        
        $sort = '[
            {
              "property": "placa",
              "direction": "ASC"
            }
          ]';        
        $page = 1;
        $limit = 10;  
        $relations = '[
            "empresa"
          ]';  
        $parameters = [
            'filters' => $filters,
            'sort' => $sort,
            'page' => $page,
            'limit' => $limit,
            'relations'=>$relations
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
        $this->assertEquals("QGQ518", $obj->data[0]->placa);      
        $this->assertEquals("QHH783", $obj->data[1]->placa);
        $this->assertEquals("SID435", $obj->data[2]->placa);
        $this->assertEquals("SMG843", $obj->data[3]->placa);
        $this->assertEquals("SRO647", $obj->data[4]->placa);
        $this->assertEquals("SSY545", $obj->data[5]->placa);
        $this->assertEquals("SSY571", $obj->data[6]->placa);
        $this->assertEquals("SSY803", $obj->data[7]->placa);
        $this->assertEquals("SSZ544", $obj->data[8]->placa);
        $this->assertEquals("SSZ546", $obj->data[9]->placa);
    }
    
    public function testFilterBTW()
    {
        //eq, lt, gt, lte, gte, btw,  dif, isnotnull, isnull
                
        //Peticion orden descendente
        $method = 'GET';
        $uri = self::$uriTest;
        
        $filters ='
            {
                "and":[
                    {
                        "field": "capacidad_combustible",
                        "comparison": "btw",
                        "value": [120,140]
                    }            
                ]
            }';        
        $sort = '[
            {
              "property": "placa",
              "direction": "ASC"
            }
          ]';        
        $page = 1;
        $limit = 10;  
        $relations = '[
            "empresa"
          ]';  
        $parameters = [
            'filters' => $filters,
            'sort' => $sort,
            'page' => $page,
            'limit' => $limit,
            'relations'=>$relations
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
        $this->assertEquals("QGQ518", $obj->data[0]->placa);      
        $this->assertEquals("QHH783", $obj->data[1]->placa);
        $this->assertEquals("SID435", $obj->data[2]->placa);
        $this->assertEquals("SMG843", $obj->data[3]->placa);
        $this->assertEquals("SRO647", $obj->data[4]->placa);
        $this->assertEquals("SSY545", $obj->data[5]->placa);
        $this->assertEquals("SSY571", $obj->data[6]->placa);
        $this->assertEquals("SSY803", $obj->data[7]->placa);
        $this->assertEquals("SSZ544", $obj->data[8]->placa);
        $this->assertEquals("SSZ546", $obj->data[9]->placa);        
    }    
    
    public function testFilterLk()
    {
        //eq, lt, gt, lte, gte, btw,  dif, isnotnull, isnull
                
        //Peticion orden descendente
        $method = 'GET';
        $uri = self::$uriTest;
        
        $filters ='
            {
                "and":[
                    {
                        "field": "placa",
                        "comparison": "lk",
                        "value": "SSZ"
                    }          
                ]
            }';        
        $sort = '[
            {
              "property": "placa",
              "direction": "ASC"
            }
          ]';        
        $page = 1;
        $limit = 10;  
        $relations = '[
            "empresa"
          ]';  
        $parameters = [
            'filters' => $filters,
            'sort' => $sort,
            'page' => $page,
            'limit' => $limit,
            'relations'=>$relations
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
        $this->assertEquals("SSZ363", $obj->data[0]->placa);      
        $this->assertEquals("SSZ497", $obj->data[1]->placa);
        $this->assertEquals("SSZ498", $obj->data[2]->placa);
        $this->assertEquals("SSZ506", $obj->data[3]->placa);
        $this->assertEquals("SSZ507", $obj->data[4]->placa);
        $this->assertEquals("SSZ508", $obj->data[5]->placa);
        $this->assertEquals("SSZ509", $obj->data[6]->placa);
        $this->assertEquals("SSZ510", $obj->data[7]->placa);
        $this->assertEquals("SSZ511", $obj->data[8]->placa);
        $this->assertEquals("SSZ512", $obj->data[9]->placa);   
    }
    
    public function testFilterIsNull()
    {
        //eq, lt, gt, lte, gte, btw,  dif, isnotnull, isnull
                
        //Peticion orden descendente
        $method = 'GET';
        $uri = self::$uriTest;
        
        $filters ='
            {
                "and":
                [
                    {
                    "field": "ultimo_login",
                    "comparison": "isnull"
                    }            
                ]
            }
            ';        
        $sort = '[
            {
              "property": "placa",
              "direction": "ASC"
            }
          ]';        
        $page = 1;
        $limit = 10;  
        $relations = '[
            "empresa"
          ]';  
        $parameters = [
            'filters' => $filters,
            'sort' => $sort,
            'page' => $page,
            'limit' => $limit,
            'relations'=>$relations
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
        $this->assertEquals("QGQ518", $obj->data[0]->placa);      
        $this->assertEquals("QQQ111", $obj->data[1]->placa);
        $this->assertEquals("SSY545", $obj->data[2]->placa);
        $this->assertEquals("SSY546", $obj->data[3]->placa);
        $this->assertEquals("STA002", $obj->data[4]->placa);
        $this->assertEquals("STA003", $obj->data[5]->placa);
        $this->assertEquals("STA009", $obj->data[6]->placa);
        $this->assertEquals("STS250", $obj->data[7]->placa);
        $this->assertEquals("SUF767", $obj->data[8]->placa);
        $this->assertEquals("SY75C", $obj->data[9]->placa);        
    }    
    
    public function testFilterIsNotNull()
    {
        //eq, lt, gt, lte, gte, btw,  dif, isnotnull, isnull
                
        //Peticion orden descendente
        $method = 'GET';
        $uri = self::$uriTest;
        
        $filters ='
            {
                "and":[
                    {
                    "field": "ultimo_login",
                    "comparison": "isnotnull"
                    }            
                ]
            }';        
        $sort = '[
            {
              "property": "placa",
              "direction": "ASC"
            }
          ]';        
        $page = 1;
        $limit = 10;  
        $relations = '[
            "empresa"
          ]';  
        $parameters = [
            'filters' => $filters,
            'sort' => $sort,
            'page' => $page,
            'limit' => $limit,
            'relations'=>$relations
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
        $this->assertEquals("GGN920", $obj->data[0]->placa);      
        $this->assertEquals("NNN000", $obj->data[1]->placa);
        $this->assertEquals("PPP111", $obj->data[2]->placa);
        $this->assertEquals("PVJ161", $obj->data[3]->placa);
        $this->assertEquals("QGQ240", $obj->data[4]->placa);
        $this->assertEquals("QHH026", $obj->data[5]->placa);
        $this->assertEquals("QHH782", $obj->data[6]->placa);
        $this->assertEquals("QHH783", $obj->data[7]->placa);
        $this->assertEquals("QHI124", $obj->data[8]->placa);
        $this->assertEquals("QHL043", $obj->data[9]->placa);        
    } 
    
    public function testFilterLteGte()
    {
        //eq, lt, gt, lte, gte, btw,  dif, isnotnull, isnull
                
        //Peticion orden descendente
        $method = 'GET';
        $uri = self::$uriTest;
        
        $filters ='
            {
                "and":[
                    {
                        "field": "ultimo_login",
                        "comparison": "gte",
                        "value": "2015-07-11"
                    },
                    {
                        "field": "ultimo_login",
                        "comparison": "lte",
                        "value": "2015-07-12"
                    }            
                ]
            }';        
        $sort = '[
            {
              "property": "placa",
              "direction": "ASC"
            }
          ]';        
        $page = 1;
        $limit = 10;  
        $relations = '[
            "empresa"
          ]';  
        $parameters = [
            'filters' => $filters,
            'sort' => $sort,
            'page' => $page,
            'limit' => $limit,
            'relations'=>$relations
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
        $this->assertEquals("GGN920", $obj->data[0]->placa);      
        $this->assertEquals("QHL045", $obj->data[1]->placa);
        $this->assertEquals("SMG864", $obj->data[2]->placa);
        $this->assertEquals("SSY547", $obj->data[3]->placa);
        $this->assertEquals("SSZ547", $obj->data[4]->placa);
        $this->assertEquals("SSZ824", $obj->data[5]->placa);
        $this->assertEquals("SSZ825", $obj->data[6]->placa);
        $this->assertEquals("STS534", $obj->data[7]->placa);
        $this->assertEquals("SUF766", $obj->data[8]->placa);
        $this->assertEquals("SXR620", $obj->data[9]->placa);        
    }
    
    public function testFilterBTWE()
    {
        //eq, lt, gt, lte, gte, btw,  dif, isnotnull, isnull
                
        //Peticion orden descendente
        $method = 'GET';
        $uri = self::$uriTest;
        
        $filters ='
            {
                "and":[
                    {
                        "field": "ultimo_login",
                        "comparison": "btwe",
                        "value": ["2015-07-11","2015-07-12"]
                    }            
                ]
            }';        
        $sort = '[
            {
              "property": "placa",
              "direction": "ASC"
            }
          ]';        
        $page = 1;
        $limit = 10;  
        $relations = '[
            "empresa"
          ]';  
        $parameters = [
            'filters' => $filters,
            'sort' => $sort,
            'page' => $page,
            'limit' => $limit,
            'relations'=>$relations
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
        $this->assertEquals("GGN920", $obj->data[0]->placa);      
        $this->assertEquals("QHL045", $obj->data[1]->placa);
        $this->assertEquals("SMG864", $obj->data[2]->placa);
        $this->assertEquals("SSY547", $obj->data[3]->placa);
        $this->assertEquals("SSZ547", $obj->data[4]->placa);
        $this->assertEquals("SSZ824", $obj->data[5]->placa);
        $this->assertEquals("SSZ825", $obj->data[6]->placa);
        $this->assertEquals("STS534", $obj->data[7]->placa);
        $this->assertEquals("SUF766", $obj->data[8]->placa);
        $this->assertEquals("SXR620", $obj->data[9]->placa);        
    }    
    
    public function testFilterDif()
    {
        //eq, lt, gt, lte, gte, btw,  dif, isnotnull, isnull
                
        //Peticion orden descendente
        $method = 'GET';
        $uri = self::$uriTest;
        
        $filters ='
            {
            "and":[
                    {
                        "field": "capacidad_combustible",
                        "comparison": "dif",
                        "value": 181
                    },
                    {
                        "field": "capacidad_combustible",
                        "comparison": "dif",
                        "value": 164
                    },
                    {
                        "field": "capacidad_combustible",
                        "comparison": "dif",
                        "value": 123
                    },
                    {
                        "field": "capacidad_combustible",
                        "comparison": "dif",
                        "value": 168
                    }
                ]
            }';        
        $sort = '[
            {
              "property": "placa",
              "direction": "ASC"
            }
          ]';        
        $page = 1;
        $limit = 10;  
        $relations = '[
            "empresa"
          ]';  
        $parameters = [
            'filters' => $filters,
            'sort' => $sort,
            'page' => $page,
            'limit' => $limit,
            'relations'=>$relations
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
        $this->assertEquals("NNN000", $obj->data[0]->placa);      
        $this->assertEquals("PVJ161", $obj->data[1]->placa);
        $this->assertEquals("QGQ240", $obj->data[2]->placa);
        $this->assertEquals("QHH026", $obj->data[3]->placa);
        $this->assertEquals("QHH783", $obj->data[4]->placa);
        $this->assertEquals("QHI124", $obj->data[5]->placa);
        $this->assertEquals("QHL043", $obj->data[6]->placa);
        $this->assertEquals("QHL045", $obj->data[7]->placa);
        $this->assertEquals("QHR555", $obj->data[8]->placa);
        $this->assertEquals("QIA078", $obj->data[9]->placa);        
    }    
    
    public function testWith()
    {   
        //Peticion orden descendente
        $method = 'GET';
        $uri = self::$uriTest;
        
        $sort = '[
            {
              "property": "placa",
              "direction": "ASC"
            }
          ]';        
        $page = 1;
        $limit = 10;  
        $relations = '[
            "empresa"
          ]';  
        $parameters = [
            'sort' => $sort,
            'page' => $page,
            'limit' => $limit,
            'relations'=>$relations
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
        $this->assertEquals("GGN920", $obj->data[0]->placa);
        $this->assertEquals("864038", $obj->data[0]->empresa->id_empresa);
        $this->assertEquals("INGENIERIA VENGAL", $obj->data[0]->empresa->nombre);

        $this->assertEquals("NNN000", $obj->data[1]->placa);
        $this->assertEquals("864036", $obj->data[1]->empresa->id_empresa);
        $this->assertEquals("TLCSA", $obj->data[1]->empresa->nombre);
        
        
        $this->assertEquals("PPP111", $obj->data[2]->placa);
        $this->assertEquals("864038", $obj->data[2]->empresa->id_empresa);
        $this->assertEquals("INGENIERIA VENGAL", $obj->data[2]->empresa->nombre);

        $this->assertEquals("PVJ161", $obj->data[3]->placa);
        $this->assertEquals("864031", $obj->data[3]->empresa->id_empresa);
        $this->assertEquals("Itelca S.A.S.", $obj->data[3]->empresa->nombre);
        
        
        $this->assertEquals("QGQ240", $obj->data[4]->placa);
        $this->assertEquals("864036", $obj->data[4]->empresa->id_empresa);
        $this->assertEquals("TLCSA", $obj->data[4]->empresa->nombre);
        
        $this->assertEquals("QGQ518", $obj->data[5]->placa);
        $this->assertEquals("864036", $obj->data[5]->empresa->id_empresa);
        $this->assertEquals("TLCSA", $obj->data[5]->empresa->nombre);
        
        $this->assertEquals("QHH026", $obj->data[6]->placa);
        $this->assertEquals("864036", $obj->data[6]->empresa->id_empresa);
        $this->assertEquals("TLCSA", $obj->data[6]->empresa->nombre);
        
        $this->assertEquals("QHH782", $obj->data[7]->placa);
        $this->assertEquals("864031", $obj->data[7]->empresa->id_empresa);
        $this->assertEquals("Itelca S.A.S.", $obj->data[7]->empresa->nombre);
        
        $this->assertEquals("QHH783", $obj->data[8]->placa);
        $this->assertEquals("864036", $obj->data[8]->empresa->id_empresa);
        $this->assertEquals("TLCSA", $obj->data[8]->empresa->nombre);
        
        $this->assertEquals("QHI124", $obj->data[9]->placa);        
        $this->assertEquals("864036", $obj->data[9]->empresa->id_empresa);
        $this->assertEquals("TLCSA", $obj->data[9]->empresa->nombre);
    }  
    
    public function testFilterOR()
    {
        //eq, lt, gt, lte, gte, btw,  dif, isnotnull, isnull
                
        //Peticion orden descendente
        $method = 'GET';
        $uri = self::$uriTest;
        
        $filters ='
            {
            "or":[
                {
                    "field": "capacidad_combustible",
                    "comparison": "eq",
                    "value": 100
                },
                {
                    "field": "capacidad_combustible",
                    "comparison": "eq",
                    "value": 120
                }            
            ]
            }';        
        $sort = '[
            {
              "property": "placa",
              "direction": "DESC"
            }
          ]';        
        $page = 1;
        $limit = 10;  
        $relations = '[
            "empresa"
          ]';  
        $parameters = [
            'filters' => $filters,
            'sort' => $sort,
            'page' => $page,
            'limit' => $limit,
            'relations'=>$relations
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
        $this->assertEquals("UYX450", $obj->data[0]->placa);
        $this->assertEquals("SSZ827", $obj->data[1]->placa);
        $this->assertEquals("SSZ819", $obj->data[2]->placa); 
    }
    
    //No soporta elementos relacionados
    public function testFilterOneToOneWith()
    {
        //eq, lt, gt, lte, gte, btw,  dif, isnotnull, isnull
                
        //Peticion orden descendente
        $method = 'GET';
        $uri = self::$uriTest;
        
        $filters ='
            {
                "and":[
                    {
                    "field": "empresa.nombre",
                    "comparison": "eq",
                    "value": "Javier Canon"
                    }      
                ]
            }';        
        $sort = '[
            {
              "property": "placa",
              "direction": "ASC"
            }
          ]';        
        $page = 1;
        $limit = 10;  
        $relations = '[
            "empresa"
          ]';  
        $parameters = [
            'filters' => $filters,
            'sort' => $sort,
            'page' => $page,
            'limit' => $limit,
            'relations'=>$relations
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
        $this->assertEquals("UYX450", $obj->data[0]->placa); 
    }   
    
    public function testFilterOneToManyWith()
    {
        //eq, lt, gt, lte, gte, btw,  dif, isnotnull, isnull
                
        //Peticion orden descendente
        $method = 'GET';
        $uri = self::$uriTest;
        
        $filters ='
            {
                "and":[
                    {
                        "field": "dispositivos.imei",
                        "comparison": "eq",
                        "value": "356612024702450"
                    }      
                ]
            }';        
        $sort = '[
            {
              "property": "placa",
              "direction": "ASC"
            }
          ]';        
        $page = 1;
        $limit = 10;  
        $relations = '[
            "empresa"
          ]';  
        $parameters = [
            'filters' => $filters,
            'sort' => $sort,
            'page' => $page,
            'limit' => $limit,
            'relations'=>$relations
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
        $this->assertEquals("TAW088", $obj->data[0]->placa); 
    }    
    
    //No soportado
    public function testFilterManyToManyWith()
    {
        //eq, lt, gt, lte, gte, btw,  dif, isnotnull, isnull
                
        //Peticion orden descendente
        $method = 'GET';
        $uri = self::$uriTest;
        
        $filters ='
            {
            "and":[
                    {
                        "field": "conductores.num_documento",
                        "comparison": "eq",
                        "value": "79481786"
                    }      
                ]
            }';        
        $sort = '[
            {
              "property": "placa",
              "direction": "ASC"
            }
          ]';        
        $page = 1;
        $limit = 10;  
        $relations = '[
            "empresa"
          ]';  
        $parameters = [
            'filters' => $filters,
            'sort' => $sort,
            'page' => $page,
            'limit' => $limit,
            'relations'=>$relations
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
        $this->assertEquals("SSZ515", $obj->data[0]->placa); 
        $this->assertEquals("STS329", $obj->data[1]->placa); 
    }
    
    //No soportado porque está relacionado
    public function testFilterANDORWith()
    {
        //eq, lt, gt, lte, gte, btw,  dif, isnotnull, isnull
                
        //Peticion orden descendente
        $method = 'GET';
        $uri = self::$uriTest;
        
        $filters ='
            {
                "and":[
                      {
                        "field": "empresa.nombre",
                        "comparison": "eq",
                        "value": "TLCSA"
                      } ,
                      {
                        "or":[
                            {
                                "field": "conductores.num_documento",
                                "comparison": "eq",
                                "value": "12345667"
                            },
                            {
                                "field": "conductores.num_documento",
                                "comparison": "eq",
                                "value": "79481786"            
                            }
                        ]
                      }
                ]
            }';      
        $sort = '[
            {
              "property": "placa",
              "direction": "ASC"
            }
          ]';        
        $page = 1;
        $limit = 10;  
        $relations = '[
            "empresa"
          ]';  
        $parameters = [
            'filters' => $filters,
            'sort' => $sort,
            'page' => $page,
            'limit' => $limit,
            'relations'=>$relations
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
        $this->assertEquals("SSY916", $obj->data[0]->placa); 
        $this->assertEquals("SSZ515", $obj->data[1]->placa);         
        $this->assertEquals("STS329", $obj->data[2]->placa);
        
    }
}
