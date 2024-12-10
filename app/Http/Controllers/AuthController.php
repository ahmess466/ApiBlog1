<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request){
        // $validated = $request->validate([
        //     'name' => 'required|string|max:255',
        //     'email' => 'required|email|string|max:255|unique:users',
        //     'password' => 'required|string|min:8|confirmed',
        // ]);
        $validated = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email' => 'required|email|string|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
        if($validated->fails()){
            return response()->json($validated->errors(),status:403);
        }
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
             ]);

             $token = $user->createToken('auth_token')->plainTextToken;  //** */

             //return
             return response()->json([
                 'access_token' => $token,
                 'user' => $user
             ],status:200);
        } catch (\Exception $expection)
        {
            return response()->json(['error' => $expection->getMessage()], status:403);
            //throw $th;
        }




    }
    public function login(Request $request)
{
    // التحقق من صحة البيانات
    $validated = Validator::make($request->all(), [
        'email' => 'required|email|string',
        'password' => 'required|string|min:6',
    ]);

    // في حالة فشل التحقق
    if ($validated->fails()) {
        return response()->json($validated->errors(), 403); // تم تصحيح صيغة "status"
    }

    // إعداد بيانات الاعتماد
    $credentials = ['email' => $request->email, 'password' => $request->password];

    try {
        // محاولة تسجيل الدخول
        if (!auth()->attempt($credentials)) {
            return response()->json(['error' => 'Invalid Credentials'], 403);
        }

        // جلب المستخدم
        $user = User::where('email', $request->email)->firstOrFail();

        // إنشاء رمز الدخول
        $token = $user->createToken('auth_token')->plainTextToken;

        // الاستجابة
        return response()->json([
            'access_token' => $token,
            'user' => $user
        ], 200);
    } catch (\Exception $exception) {
        // التعامل مع الأخطاء
        return response()->json(['error' => $exception->getMessage()], 500); // تم تصحيح "status"
    }
}

public function logout(Request $request)
{
    try {
        $token = $request->user()->currentAccessToken();
        if ($token) {
            $token->delete(); // Delete the current token if it exists
        }
        return response()->json(['message' => 'Logged out successfully'], 200);
    } catch (\Exception $exception) {
        return response()->json(['error' => 'Unable to logout: ' . $exception->getMessage()], 500);
    }
}



}
