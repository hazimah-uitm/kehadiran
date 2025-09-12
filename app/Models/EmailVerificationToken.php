<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailVerificationToken extends Model
{
    protected $fillable = ['user_id', 'token'];
}
