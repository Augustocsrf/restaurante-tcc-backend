<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = "order_items";

    protected $fillable = ["quantity", "name", "price", "comment", "item_id", "order_id"];
}
