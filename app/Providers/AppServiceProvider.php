<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use MongoDB\BSON\ObjectID;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('uniqueInCompany', function($attribute, $value, $parameters) {
            $collection = $parameters[0];
            $field = $parameters[1];
            $idException = $parameters[2];
            $idFieldName = $parameters[3];
            $idCompanyFieldName = $parameters[4];
            $idCompany = $parameters[5];

            $query = DB::collection($collection)->where($attribute, $value);

            if(strtoupper($idException) != 'NULL'){
                $query->where($idFieldName, '<>', new ObjectID($idException));
            }

            if(strtoupper($idCompany) != 'NULL'){
                $query->where('id_company', '=', ['$ref' => 'companies', '$id' =>new ObjectID($idCompany)]);
            }

            for($i = 6; $i < count($parameters); $i = $i + 2){
                if(strtoupper($parameters[$i+1]) == 'NULL') {
                    $parameters[$i+1] = null;
                }
                $query->where($parameters[$i], '=', $parameters[$i+1]);
            }

            $total = $query->count();

            return $total == 0;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
