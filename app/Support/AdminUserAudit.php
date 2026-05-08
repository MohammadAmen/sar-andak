<?php

namespace App\Support;

use App\Models\AdminUserAuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminUserAudit
{
    public static function log(int $targetUserId, string $action, ?array $meta = null, ?Request $request = null): void
    {
        $request ??= request();

        AdminUserAuditLog::query()->create([
            'target_user_id' => $targetUserId,
            'super_admin_id' => Session::get('super_admin_id'),
            'action' => $action,
            'meta' => $meta,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent() ? mb_substr($request->userAgent(), 0, 512) : null,
        ]);
    }
}
