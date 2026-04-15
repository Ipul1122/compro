<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\DashboardController;

/*
|--------------------------------------------------------------------------
| Public Routes (Tidak butuh token)
|--------------------------------------------------------------------------
*/

// Route untuk Login
Route::post('/login', [AuthController::class, 'login']);

// Endpoint Publik untuk Frontend Utama (Hanya read-only)
Route::get('/categories', [CategoryController::class, 'index']); // List semua kategori
Route::get('/categories/{slug}', function ($slug) {
    $category = \App\Models\Category::where('slug', $slug)->firstOrFail();
    return response()->json([
        'status' => 'success',
        'data' => $category
    ]);
});


/*
|--------------------------------------------------------------------------
| Protected Routes (Butuh Token Sanctum)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {
    // Ambil data user yang sedang login
    Route::get('/user', function (Request $request) {
        return response()->json([
            'status' => 'success',
            'data' => $request->user()
        ]);
    });

    // Route untuk Logout
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Group khusus Admin Panel
    Route::prefix('admin')->group(function () {
        // Dashboard Rekapitulasi
        Route::get('/dashboard', [DashboardController::class, 'index']);
        
        // CRUD Category (Hanya method Store, Update, Destroy, Show berdasarkan ID)
        // Method Index() sudah ada di public route, tapi kalau admin butuh route sendiri bisa pakai apiResource
        Route::apiResource('categories', CategoryController::class);
    });
});