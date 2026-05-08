<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminUserAuditLog extends Model
{
    protected $fillable = [
        'target_user_id',
        'super_admin_id',
        'action',
        'meta',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'meta' => 'array',
        ];
    }

    public function targetUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }

    public function superAdmin(): BelongsTo
    {
        return $this->belongsTo(SuperAdmin::class, 'super_admin_id');
    }
}
