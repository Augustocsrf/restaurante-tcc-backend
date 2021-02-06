<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = "items";

    protected $fillable = ["name", "description", "price", "active", "category_id"];

    function category(){
        return $this->belongsTo('Category', 'id');
    }
}
