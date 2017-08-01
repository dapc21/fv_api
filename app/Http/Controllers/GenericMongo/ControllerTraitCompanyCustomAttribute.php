<?php

namespace App\Http\Controllers\GenericMongo;

use App\datatraffic\lib\Util;

Trait ControllerTraitCompanyCustomAttribute
{
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

        return $customAttributes;
    }
}
