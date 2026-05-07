<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtpCodes extends Model
{
    use HasFactory;

    // تحديد اسم الجدول إذا كان مختلفاً عن جمع اسم الموديل
    protected $table = 'otp_codes';

    // الحقول المسموح بتعبئتها (Mass Assignment)
    protected $fillable = [
        'phone',
        'code',
        'expires_at',
        'verified',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'verified' => 'boolean',
    ];

    public function isExpired()
    {
        return $this->expires_at->isPast();
    }
}
