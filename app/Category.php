<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = ['name', 'active'];

    function items(){
        return $this->hasMany('Item', 'id');
    }
}