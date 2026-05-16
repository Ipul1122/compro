<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppNotification;
use App\Models\Article;
use App\Models\ArticleActivity;
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

    public function articleActivities(Request $request)
    {
        $limit = (int) $request->query('limit', 12);
        $limit = max(1, min($limit, 50));

        $activities = ArticleActivity::query()
            ->with([
                'article:id,title,slug',
                'user:id,name',
            ])
            ->latest()
            ->limit($limit)
            ->get()
            ->map(function ($activity) {
                $time = $activity->created_at;
                return [
                    'article_id' => $activity->article_id,
                    'title' => $activity->article?->title ?? 'Artikel',
                    'slug' => $activity->article?->slug ?? null,
                    'author_name' => $activity->user?->name ?? 'Tanpa Nama',
                    'action' => $activity->action,
                    'action_label' => $activity->action === 'edited' ? 'mengedit artikel' : 'membuat artikel',
                    'happened_at' => optional($time)->toDateTimeString(),
                    'happened_at_human' => optional($time)->diffForHumans(),
                    'is_recent' => $time ? $time->greaterThan(now()->subDay()) : false,
                ];
            })
            ->values();

        return response()->json([
            'success' => true,
            'message' => 'Aktivitas artikel terbaru berhasil diambil',
            'data' => $activities
        ], 200);
    }

    public function notifications(Request $request)
    {
        $user = $request->user();
        $limit = (int) $request->query('limit', 10);
        $limit = max(1, min($limit, 50));

        $items = AppNotification::query()
            ->where('recipient_user_id', $user->id)
            ->where(function ($query) {
                $query->whereNull('target_type')
                    ->orWhere('target_type', '!=', 'article')
                    ->orWhere('event_type', 'article_created');
            })
            ->latest()
            ->limit($limit)
            ->get()
            ->map(function ($n) {
                return [
                    'id' => $n->id,
                    'title' => $n->title,
                    'message' => $n->message,
                    'url' => $n->target_type === 'article' && $n->event_type === 'article_created'
                        ? '/direktur/articles?status=pending'
                        : $n->url,
                    'is_read' => $n->is_read,
                    'created_at' => optional($n->created_at)->toDateTimeString(),
                    'created_at_human' => optional($n->created_at)->diffForHumans(),
                ];
            })
            ->values();

        $unreadCount = AppNotification::query()
            ->where('recipient_user_id', $user->id)
            ->where(function ($query) {
                $query->whereNull('target_type')
                    ->orWhere('target_type', '!=', 'article')
                    ->orWhere('event_type', 'article_created');
            })
            ->where('is_read', false)
            ->count();

        return response()->json([
            'success' => true,
            'data' => $items,
            'meta' => ['unread_count' => $unreadCount]
        ]);
    }

    public function markNotificationsRead(Request $request)
    {
        $user = $request->user();
        $ids = $request->input('ids', []);

        $query = AppNotification::query()
            ->where('recipient_user_id', $user->id)
            ->where('is_read', false);

        if (is_array($ids) && count($ids) > 0) {
            $query->whereIn('id', $ids);
        }

        $query->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi telah ditandai sebagai dibaca'
        ]);
    }
}
