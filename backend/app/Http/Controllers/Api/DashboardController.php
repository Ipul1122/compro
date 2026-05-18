<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\User;
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

    public function globalSearch(Request $request)
    {
        $keyword = $request->search;

        // Jika tidak ada keyword yang diketik, kembalikan array kosong
        if (!$keyword) {
            return response()->json([
                'success' => true,
                'data' => [
                    'articles' => [],
                    'categories' => []
                ]
            ], 200);
        }

        // Cari 5 artikel yang cocok
        $articles = Article::select('id', 'title', 'slug', 'image')
            ->where('title', 'like', '%' . $keyword . '%')
            ->take(5)->get();

        // Cari 5 kategori yang cocok
        $categories = Category::select('id', 'name', 'slug')
            ->where('name', 'like', '%' . $keyword . '%')
            ->take(5)->get();

        return response()->json([
            'success' => true,
            'message' => 'Hasil pencarian global',
            'data'    => [
                'articles'   => $articles,
                'categories' => $categories
            ]
        ], 200);
    }

    public function getAllUsers()
    {
        $users = User::select('id', 'name', 'email')->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar semua penulis/users',
            'data' => $users
        ], 200);
    }
<<<<<<< HEAD
}
=======


}
>>>>>>> 92a43e72cebbbbed91af592e6268f013c2ddcef7
