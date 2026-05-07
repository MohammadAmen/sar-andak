<?php

namespace App\Http\Controllers;

use App\Models\Areas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'address_details' => 'nullable|string',
            'area_id' => 'required|exists:areas,id', // التأكد من وجود المنطقة
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user->name = $request->name;
        $user->address_details = $request->address_details;
        $user->area_id = $request->area_id;

        if ($request->hasFile('image')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $path = $request->file('image')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();

        return response()->json([
            'status' => 'success',
            'user' => $user->load('area') // تحميل بيانات المنطقة
        ]);
    }

    // دالة جديدة لجلب المناطق ليختار منها العميل
    public function getAreas()
    {
        return response()->json(Areas::all());
    }
}