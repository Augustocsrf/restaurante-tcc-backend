<?php

namespace App;

use App\OrderItem;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = "orders";

    protected $fillable = ["price", "payment_method", "cash", "delivery_method", "order_status_id", "address_id", "client_id"];

    public function order_items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
