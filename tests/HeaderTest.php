<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class HeaderTest extends TestCase
{
    public function testApplication()
{
    $strToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiI1NzEwMTJkZTI4Nzc4NGY5MWI3ZjBmOWYiLCJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMFwvbG9naW4iLCJpYXQiOjE0NjE3OTAwODAsImV4cCI6MTQ2MTc5MzY4MCwibmJmIjoxNDYxNzkwMDgwLCJqdGkiOiJjZDhiMTYwN2RkNGEyOTBlYmFjOTAwODRlOWJjNjIzZCJ9.NdnM4xfsls2esyDIz2YxAm6S_ui88vgZj0D1neQ_yBI"';
    
    $method = 'GET';
    $uri = '/companies';//?token=' .  $strToken;
    $parameters = array();
    $cookies = array(); 
    $files = array();
    $server = array();
    $content = null;
    
    //Colocando Header
    $server = ['HTTP_Authorization' => ('Bearer '.$strToken)];
    
    //dd($server);
    
    $response = $this->call($method, $uri, $parameters, $cookies, $files, $server, $content);

    dd(json_decode($response->getContent()));
    
    $this->assertEquals(200, $response->status());
}
}
