<?php

namespace App\Http\Controllers\GenericMongo;


use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\datatraffic\lib\Util;

class CompanyScope implements Scope {

    public function apply(Builder $builder, Model $model) {
        $table = $model->getTable();
        $builder->where('id_company', '=', Util::$insUser->id_company);
    }
}
