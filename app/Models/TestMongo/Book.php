<?php

namespace App\Models\TestMongo;

use Jenssegers\Mongodb\Model as Eloquent;

class Book extends Eloquent {

    protected $guarded = array('title');
    /*public function books()
    {
        return $this->embedsMany('Book');
    }*/

}
