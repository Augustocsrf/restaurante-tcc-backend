<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $table = "reservations";

    protected $fillable = ["day", "time", "name", "lastName", "guests", "client_id"];
}
