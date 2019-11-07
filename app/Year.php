<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Year extends Model
{
    protected $table = 'years';


    public function user(){
        return $this->hasMany('App\User', 'year', 'id');
    }
}
