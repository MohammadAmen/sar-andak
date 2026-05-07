<?php

use App\Http\Controllers\Api\Driver\DriverOrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Models\Areas;
use Illuminate\Http\Request;

Route::post('/send-otp', [AuthController::class, 'sendOtp']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);


Route::middleware('auth:sanctum')->post('/user/update', [UserController::class, 'updateProfile']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    // جلب المستخدم مع العلاقة الخاصة بالمنطقة
    $user = $request->user()->load('area');

    return response()->json([
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'phone' => $user->phone, // مفيد جداً عرضه في البروفايل
            'area_id' => $user->area_id, // نحتاجه لضبط الـ Picker في الفرونت
            'area_name' => $user->area ? $user->area->name : 'لم تحدد منطقة',
            'delivery_fee' => $user->area ? $user->area->delivery_fee : 0,
            'address_details' => $user->address_details,
            'avatar_url' => $user->avatar ? asset('storage/' . $user->avatar) : null,
            'role' => $user->role, // مهم جداً للتوجيه في التطبيق
        ]
    ]);
});

Route::middleware('auth:sanctum')->post('/orders/custom', [OrderController::class, 'storeCustomOrder']);
Route::middleware('auth:sanctum')->post('/orders/water-tanker', [OrderController::class, 'storeWaterTankerOrder']);

Route::middleware('auth:sanctum')->get('/orders', [OrderController::class, 'index']);
Route::middleware('auth:sanctum')->get('/orders/{id}', [OrderController::class, 'show']);


Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('driver')->group(function () {
        Route::get('/available-orders', [DriverOrderController::class, 'getAvailableOrders']);
        Route::get('/water-tanker/tasks', [DriverOrderController::class, 'waterTankerTasks']);
        Route::post('/orders/{id}/accept', [DriverOrderController::class, 'acceptOrder']);
        Route::post('/orders/{id}/update-status', [DriverOrderController::class, 'updateStatus']);
        Route::post('/orders/{id}/driver-location', [DriverOrderController::class, 'updateDriverLocation']);
    });


    Route::get('/areas', function () {
    return response()->json(Areas::all());
});

});
