<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //Pruebas
        'test',
        //Todos
        '*',
        //Autenticación
        'login',
        'logout',
        //Usuarios
        'users',
        'users/*',
        'users/*/restore',
        'users/*/changepassword',
        'users/resetpassword/*',
        //Roles
        'roles',
        'roles/*',
        //Aplicaciones
        'applications',
        'applications/*',
        //Compañias
        'companies',
        'companies/*',
        'companies/*/licenses',
        'companies/*/licenses/*',
        //Definiciones de Dispositivos
        'devicesdefinitions',
        'devicesdefinitions/*',
        'devicesdefinitions/*/customsattributes',
        'devicesdefinitions/*/customsattributes/*',
        //Instancias de Dispositivos
        'devicesinstances',
        'devicesinstances/*',
        //Plantillas de Recursos
        'resourcestemplates',
        'resourcestemplates/*',
        //Plantillas de Recursos
        'resourcesdefinitions',
        'resourcesdefinitions/*',
        'resourcesdefinitions/*/customsattributes',
        'resourcesdefinitions/*/customsattributes/*',
        'resourcesdefinitions/*/devicesdefinitions',
        'resourcesdefinitions/*/devicesdefinitions/*',
        'resourcesdefinitions/*/resourcesdefinitions',
        'resourcesdefinitions/*/resourcesdefinitions/*',
        'resources',
        'tracking/actual',
        'tasks'
    ];
}
