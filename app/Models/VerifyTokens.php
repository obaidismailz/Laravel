<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerifyTokens extends Model
{
    protected $fillable = [
        'email',
        'token',
    ];
}
