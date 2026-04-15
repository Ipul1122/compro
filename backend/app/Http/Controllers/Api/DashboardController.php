<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Menampilkan data statistik untuk halaman utama Dashboard Admin
     */
    public function index()
    {
        // 1. Hitung total kategori
        $totalCategories = Category::count();

        // 2. Hitung total artikel (semua status)
        $totalArticles = Article::count();

        // 3. (Opsional tapi disarankan) Hitung artikel berdasarkan statusnya
        $totalPublished = Article::where('published', 'publish')->count();
        $totalDraft = Article::where('published', 'draft')->count();
        
        // 4. (Opsional) Hitung total views dari semua artikel
        $totalViews = Article::sum('total_view');

        return response()->json([
            'success' => true,
            'message' => 'Data Statistik Dashboard berhasil diambil',
            'data'    => [
                'categories' => [
                    'total' => $totalCategories,
                    'link'  => '/admin/categories' // Url frontend untuk mengarahkan ke halaman kategori
                ],
                'articles' => [
                    'total'     => $totalArticles,
                    'published' => $totalPublished,
                    'draft'     => $totalDraft,
                    'views'     => $totalViews,
                    'link'      => '/admin/articles' // Url frontend untuk mengarahkan ke halaman artikel
                ]
            ]
        ], 200);
    }
}