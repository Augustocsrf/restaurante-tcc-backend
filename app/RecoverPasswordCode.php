<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecoverPasswordCode extends Model
{
    protected $table = 'recover_password_codes';

    protected $fillable = ['code', 'users_id'];
}
