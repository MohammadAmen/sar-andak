<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuperAdmin;
use App\Models\User;
use App\Models\Order;
use App\Models\Orders;
use App\Models\ProviderProfile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class SuperAdminAuthController extends Controller
{
    public function showLogin()
    {
        return view('super-admin.login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $superAdmin = SuperAdmin::query()
            ->where('phone', $validated['phone'])
            ->first();

        if (! $superAdmin || ! Hash::check($validated['password'], $superAdmin->password)) {
            return back()
                ->withInput($request->only(['phone']))
                ->withErrors(['phone' => 'رقم الجوال أو كلمة المرور غير صحيحة']);
        }

        Session::put('super_admin_id', $superAdmin->id);
        $request->session()->regenerate();

        return redirect()->route('super-admin.dashboard');
    }

    public function dashboard()
    {
        $superAdmin = SuperAdmin::find(Session::get('super_admin_id'));

        $stats = [
            'users_total' => User::query()->count(),
            'users_active' => User::query()->where('is_active', true)->count(),
            'orders_total' => Orders::query()->count(),
            'orders_pending' => Orders::query()->where('status', 'pending')->count(),
            'providers_delivery' => ProviderProfile::query()->where('provider_type', 'delivery')->count(),
            'providers_taxi' => ProviderProfile::query()->where('provider_type', 'taxi')->count(),
            'providers_water_tanker' => ProviderProfile::query()->where('provider_type', 'water_tanker')->count(),
            'providers_workshop' => ProviderProfile::query()->where('provider_type', 'workshop')->count(),
        ];

        return view('super-admin.dashboard', [
            'superAdmin' => $superAdmin,
            'stats' => $stats,
        ]);
    }

    public function logout(Request $request)
    {
        Session::forget('super_admin_id');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('super-admin.login');
    }
}
