<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReservationStatus extends Model
{
    protected $table = 'reservation_statuses';

    protected $fillable = [ "name" ];
}
