<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

// Route untuk Login (Public)
Route::post('/login', [AuthController::class, 'login']);

// Route yang butuh Token (Dilindungi)
Route::middleware('auth:sanctum')->group(function () {
    // Ambil data user yang sedang login
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Route untuk Logout
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Nanti kamu bisa tambahkan route API untuk Dashboard di dalam group ini
});