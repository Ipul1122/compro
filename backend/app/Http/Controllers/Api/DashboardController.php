<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
// use App\Models\Article; // Uncomment jika Article sudah dibuat
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCategories = Category::count();
        // $totalArticles = Article::count();
        
        // Mengambil 5 kategori terbaru untuk widget di dashboard
        $recentCategories = Category::latest()->take(5)->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'summary' => [
                    'total_categories' => $totalCategories,
                    // 'total_articles' => $totalArticles,
                ],
                'recent_categories' => $recentCategories
            ]
        ]);
    }
}