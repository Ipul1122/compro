<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validasi Input dari Frontend
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Ada kesalahan pada input data',
                'errors' => $validator->errors()
            ], 422); // 422 Unprocessable Entity
        }

        // 2. Cek Kredensial Email dan Password
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah'
            ], 401); // 401 Unauthorized
        }

        // 3. Jika berhasil, ambil data user dan buat Token
        $user = Auth::user();
        $token = $user->createToken('admin_token')->plainTextToken;

        // 4. Kirim response ke Frontend (temanmu)
        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    // Tambahkan field lain jika perlu, misal: 'role' => $user->role
                ],
                'token' => $token,
                'token_type' => 'Bearer'
            ]
        ], 200);
    }

    // Saya tambahkan method logout untuk persiapan
    public function logout(Request $request)
    {
        // Hapus token yang sedang digunakan
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil'
        ], 200);
    }
}