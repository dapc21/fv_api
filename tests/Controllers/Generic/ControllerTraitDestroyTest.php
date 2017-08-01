<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

class ControllerTraitDestroyTest extends TestCase{
    
    use DatabaseMigrations;
    
    public function testDestroy()
    {   
        //Peticion
        $method = 'DELETE';
        $uri = '/testvehiculos/864172';
      
        $parameters = [];        
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
    }    
}
