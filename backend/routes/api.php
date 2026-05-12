<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\GalleryController;

/*
|--------------------------------------------------------------------------
| Public Routes (Tidak butuh token)
|--------------------------------------------------------------------------
*/

// Artikel untuk frontend (Hanya yang published)
Route::get('/articles', [ArticleController::class, 'indexPublic']);
Route::get('/articles-list', [ArticleController::class, 'indexPublic']);
Route::get('/articles/{slug}', [ArticleController::class, 'showPublic']);
Route::get('/galleries', [GalleryController::class, 'index']);


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
    Route::get('/dashboard/search', [DashboardController::class, 'globalSearch']);
    
    // Endpoint khusus untuk dropdown form (Get All tanpa pagination)
    Route::get('categories/list', [CategoryController::class, 'list']);
    
    // CRUD Category
    Route::apiResource('categories', CategoryController::class);

    // --- RUTE BARU UNTUK PREVIEW (Tambahkan baris ini) ---
    Route::get('articles/preview/{slug}', [ArticleController::class, 'showPreview']);
    Route::apiResource('articles', ArticleController::class);
    
    Route::post('galleries/bulk-store', [GalleryController::class, 'bulkStore']);
    Route::post('galleries/bulk-delete', [GalleryController::class, 'bulkDestroy']);
    Route::apiResource('galleries', GalleryController::class);
    Route::apiResource('employees', EmployeeController::class);
    Route::post('articles/{id}/approve', [ArticleController::class, 'approve']);
    });
});

/*
|--------------------------------------------------------------------------
| ROUTE FALLBACK (Untuk menangani endpoint yang tidak ditemukan)
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    return response()->json([
        'success' => false,
        'message' => 'Endpoint API tidak ditemukan (404 Not Found).'
    ], 404);
});