<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Session::has('super_admin_id')) {
            return redirect()
                ->route('super-admin.login')
                ->withErrors(['phone' => 'يرجى تسجيل الدخول كسوبر أدمن']);
        }

        return $next($request);
    }
}
