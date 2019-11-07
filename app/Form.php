<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    protected $table = 'forms';

    public function user(){
        return $this->hasMany('App\User', 'form', 'id');
    }
}
