<?php
namespace App\Models\TestMongo;

use Jenssegers\Mongodb\Model as Eloquent;

class User extends Eloquent {
    
    public function books()
    {
        return $this->embedsMany('App\Models\TestMongo\Book');
    }

}

