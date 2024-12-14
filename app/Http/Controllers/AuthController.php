<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // التحقق من بيانات المستخدم
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // البحث عن المستخدم بناءً على البريد الإلكتروني
        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            // إنشاء التوكن
            $token = $user->createToken('YourAppName')->plainTextToken;

            // إرجاع التوكن
            return response()->json([
                'token' => $token,
                'message' => 'تم تسجيل الدخول بنجاح'
            ]);
        }

        // في حال كانت البيانات غير صحيحة
        return response()->json(['message' => 'بيانات المستخدم غير صحيحة'], 401);
    }
    public function logout(Request $request)
    {
        // حذف التوكن الحالي
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'تم تسجيل الخروج بنجاح']);
    }
    public function signup(Request $request)
    {
        // التحقق من صحة البيانات المُدخلة
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed', // يجب أن يكون هناك password_confirmation في الطلب
        ]);

        // إنشاء مستخدم جديد
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // تشفير كلمة المرور
        ]);

        // إنشاء توكن للمستخدم الجديد
        $token = $user->createToken('YourAppName')->plainTextToken;

        // إرسال الرد
        return response()->json([
            'user' => $user,
            'token' => $token,
            'message' => 'تم إنشاء الحساب بنجاح'
        ], 201);
    }
}
