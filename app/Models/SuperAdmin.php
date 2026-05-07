<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuperAdmin extends Model
{
    protected $fillable = [
        'phone',
        'password',
    ];

    protected $hidden = [
        'password',
    ];
}
