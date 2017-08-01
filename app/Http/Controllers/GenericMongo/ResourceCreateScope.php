<?php

namespace App\Http\Controllers\GenericMongo;


use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\datatraffic\lib\Util;

class ResourceCreateScope implements Scope {

    public function apply(Builder $builder, Model $model) {
        $table = $model->getTable();
        $builder->where('id_user_create', '=', Util::$insUser->getDBRef());
    }
}
