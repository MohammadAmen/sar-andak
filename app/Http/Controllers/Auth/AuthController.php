<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OtpCodes;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function sendOtp(Request $request)
    {
        $request->validate(['phone' => 'required|numeric']);

        // $code = rand(1000, 9999);
        $code = 1234;

        OtpCodes::updateOrCreate(
            ['phone' => $request->phone],
            [
                'code' => $code,
                'expires_at' => Carbon::now()->addMinutes(10),
            ]
        );

        // هنا يتم الربط مع بوابة SMS أو WhatsApp
        // مؤقتاً سنقوم بإرجاع الكود في الـ Response للتجربة (Development Only)
        return response()->json([
            'message' => 'تم إرسال كود التحقق بنجاح',
            'dev_code' => $code,
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|numeric',
            'otp' => 'required|numeric',
        ]);

        $otpRecord = OtpCodes::where('phone', $request->phone)
            ->where('code', $request->otp)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (! $otpRecord) {
            return response()->json(['message' => 'الكود غير صحيح أو منتهي الصلاحية'], 422);
        }

        $user = User::firstOrCreate(
            ['phone' => $request->phone],
            [
                'name' => 'مستخدم '.substr($request->phone, -4),
                'password' => Hash::make($request->phone.'secret'),
                'role' => 'customer',
            ]
        );

        if (! $user->is_active) {
            return response()->json([
                'message' => 'تم إيقاف هذا الحساب. تواصل مع الدعم إن كان ذلك خطأ.',
            ], 403);
        }

        $otpRecord->delete();

        $token = $user->createToken('sar_andak_token')->plainTextToken;

        return response()->json([
            'message' => 'تم تسجيل الدخول بنجاح',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'phone' => $user->phone,
                'role' => $user->role,
            ],
        ]);
    }
}
