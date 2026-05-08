<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'phone',
        'password',
        'role',
        'email',
        'area_id',
        'admin_notes',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function area()
    {
        return $this->belongsTo(Areas::class, 'area_id');
    }

    /** طلبات المستخدم كعميل (التطبيق). */
    public function customerOrders()
    {
        return $this->hasMany(Orders::class, 'customer_id');
    }

    /** طلبات السائق / مزوّد التوصيل المسندة إليه. */
    public function driverOrders()
    {
        return $this->hasMany(Orders::class, 'driver_id');
    }

    public function adminAuditLogs()
    {
        return $this->hasMany(AdminUserAuditLog::class, 'target_user_id');
    }
}
