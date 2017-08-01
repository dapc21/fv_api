<?php

namespace App\Http\Controllers\GenericMongo;


use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\datatraffic\lib\Util;

class ResourceScope implements Scope {

    public function apply(Builder $builder, Model $model) {
        $table = $model->getTable();
        $insUser = Util::$insUser;
        $builder->where(function($query) use ($insUser){
            $query->orWhere('resourceInstance._id', '=', $insUser->_id);

            $strNameCollection = $insUser->getTable();
            $insObjectID = new \MongoDB\BSON\ObjectId($insUser->getIdPrimaryKey());

            $query->orWhere('id_resourceInstance', '=', ['$ref' => $strNameCollection, '$id' => $insObjectID]);
        });
    }
}
