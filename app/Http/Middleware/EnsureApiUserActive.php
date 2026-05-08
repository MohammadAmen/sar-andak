<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureApiUserActive
{
    /**
     * قطع وصول التطبيق للحسابات المعطّلة من لوحة الإدارة.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if ($user && ! $user->is_active) {
            return response()->json([
                'message' => 'تم إيقاف حسابك. لمزيد من المعلومات تواصل مع الدعم.',
            ], 403);
        }

        return $next($request);
    }
}
