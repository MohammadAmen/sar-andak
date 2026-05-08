<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuperAdmin extends Model
{
    protected $fillable = [
        'phone',
        'password',
        'provider_scope',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'provider_scope' => 'array',
        ];
    }
}
