<?php

use App\Http\Controllers\SuperAdminAuthController;
use App\Http\Controllers\SuperAdminCustomerHomeServiceController;
use App\Http\Controllers\SuperAdminProviderController;
use App\Http\Controllers\SuperAdminUserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('super-admin.login');
});

Route::prefix('super-admin')->group(function () {
    Route::get('/login', [SuperAdminAuthController::class, 'showLogin'])->name('super-admin.login');
    Route::post('/login', [SuperAdminAuthController::class, 'login'])->name('super-admin.login.submit');
    Route::post('/logout', [SuperAdminAuthController::class, 'logout'])->name('super-admin.logout');

    Route::middleware('super-admin.auth')->group(function () {
        Route::get('/dashboard', [SuperAdminAuthController::class, 'dashboard'])->name('super-admin.dashboard');
        Route::get('/users', [SuperAdminUserController::class, 'index'])->name('super-admin.users.index');
        Route::get('/users/{user}', [SuperAdminUserController::class, 'show'])->name('super-admin.users.show');
        Route::get('/users/{user}/orders-export', [SuperAdminUserController::class, 'exportCustomerOrders'])->name('super-admin.users.orders-export');
        Route::put('/users/{user}/notes', [SuperAdminUserController::class, 'updateNotes'])->name('super-admin.users.notes');
        Route::post('/users/{user}/toggle-active', [SuperAdminUserController::class, 'toggleActive'])->name('super-admin.users.toggle-active');
        Route::get('/home-services', [SuperAdminCustomerHomeServiceController::class, 'index'])->name('super-admin.home-services.index');
        Route::put('/home-services/{homeService}', [SuperAdminCustomerHomeServiceController::class, 'update'])->name('super-admin.home-services.update');
        Route::post('/home-services/reorder', [SuperAdminCustomerHomeServiceController::class, 'reorder'])->name('super-admin.home-services.reorder');
        Route::get('/providers', [SuperAdminProviderController::class, 'index'])->name('super-admin.providers.index');
    });

    Route::middleware(['super-admin.auth', 'super-admin.provider-scope'])->group(function () {
        Route::post('/providers', [SuperAdminProviderController::class, 'store'])->name('super-admin.providers.store');
        Route::get('/providers/{providerProfile}', [SuperAdminProviderController::class, 'show'])->name('super-admin.providers.show');
        Route::put('/providers/{providerProfile}', [SuperAdminProviderController::class, 'update'])->name('super-admin.providers.update');
        Route::post('/providers/{providerProfile}/verify', [SuperAdminProviderController::class, 'verify'])->name('super-admin.providers.verify');
        Route::post('/providers/{providerProfile}/unverify', [SuperAdminProviderController::class, 'unverify'])->name('super-admin.providers.unverify');
        Route::post('/providers/{providerProfile}/toggle-active', [SuperAdminProviderController::class, 'toggleActive'])->name('super-admin.providers.toggle-active');
        Route::post('/providers/{providerProfile}/subscription/renew', [SuperAdminProviderController::class, 'renewSubscription'])->name('super-admin.providers.subscription.renew');
        Route::post('/providers/{providerProfile}/subscription/toggle-pause', [SuperAdminProviderController::class, 'toggleSubscriptionPause'])->name('super-admin.providers.subscription.toggle-pause');
    });
});
