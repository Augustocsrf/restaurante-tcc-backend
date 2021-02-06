<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'addresses';

    protected $fillable = [
        "zip", "street", "number", "district", "city", "state", "reference", "identification", "complement", "client_id"
    ];
}
