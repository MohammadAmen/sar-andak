<?php

namespace App\Http\Middleware;

use App\Models\ProviderProfile;
use App\Models\SuperAdmin;
use App\Support\ProviderStaffScope;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class EnsureSuperAdminProviderScope
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $id = Session::get('super_admin_id');
        $admin = $id ? SuperAdmin::query()->find($id) : null;

        $allowed = ProviderStaffScope::allowedTypesFor($admin);
        if ($allowed === null) {
            return $next($request);
        }

        if ($allowed === []) {
            abort(403, 'لا يوجد قطاع مزوّدين مفعّل لحسابك.');
        }

        if ($request->routeIs('super-admin.providers.store')) {
            $type = (string) $request->input('provider_type', '');
            if (! ProviderStaffScope::canAccessProviderType($admin, $type)) {
                abort(403, 'غير مسموح لك بإنشاء مزوّد في هذا القطاع.');
            }
        }

        $profile = $request->route('providerProfile');
        if ($profile instanceof ProviderProfile) {
            if (! ProviderStaffScope::canAccessProviderType($admin, $profile->provider_type)) {
                abort(403, 'غير مسموح لك بإدارة هذا الملف.');
            }
        }

        return $next($request);
    }
}
