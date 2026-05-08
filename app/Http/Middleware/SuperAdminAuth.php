<?php

namespace App\Http\Middleware;

use App\Models\SuperAdmin;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
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

        View::share('superAdmin', SuperAdmin::query()->find(Session::get('super_admin_id')));

        return $next($request);
    }
}
