<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::select('id', 'category_id', 'author_id', 'title', 'title_en', 'slug', 'image', 'published', 'total_view', 'created_at', 'updated_at')
            ->with(['category:id,name,slug', 'author:id,name,email']);

        if ($request->filled('views')) {
            $query->orderBy('total_view', $request->views);
        } else {
            $query->latest();
        }

        if ($request->filled('published')) {
            $query->where('published', $request->published);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('author_id')) {
            $query->where('author_id', $request->author_id);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('title_en', 'like', '%' . $request->search . '%');
        }

        $articles = $query->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'List Data Articles',
            'data'    => $articles->items(),
            'pagination' => [
                'total'        => $articles->total(),
                'per_page'     => $articles->perPage(),
                'current_page' => $articles->currentPage(),
                'last_page'    => $articles->lastPage(),
            ]
        ], 200);
    }

   public function indexPublic(Request $request)
    {
        // Tambahkan title_en di select
        $query = Article::select('id', 'category_id', 'title', 'title_en', 'slug', 'image', 'total_view', 'created_at')
            ->with('category:id,name,slug')
            ->where('published', 'publish');

        if ($request->has('popular')) {
            $query->orderBy('total_view', 'desc');
        } else {
            $query->latest();
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('title_en', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('limit')) {
            $articles = $query->limit($request->limit)->get();
            return response()->json([
                'success' => true,
                'data' => $articles
            ], 200);
        }

        $articles = $query->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'List Data Articles Public',
            'data'    => $articles->items(),
            'pagination' => [
                'total'        => $articles->total(),
                'per_page'     => $articles->perPage(),
                'current_page' => $articles->currentPage(),
                'last_page'    => $articles->lastPage(),
            ]
        ], 200);
    }

   public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'content' => 'required|string',
            'content_en' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'published' => 'nullable|in:draft,pending,publish'
        ]);

        $data = $request->all();
        
        // --- LOGIC BARU: MEMBUAT SLUG OTOMATIS UNIK ---
        $slug = Str::slug($request->input('title'));
        $originalSlug = $slug;
        $counter = 1;
        
        // Selama slug sudah ada di database, tambahkan angka di belakangnya
        while (Article::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }
        $data['slug'] = $slug;
        // ----------------------------------------------

        $data['author_id'] = auth()->id();
        $data['published'] = $request->input('published', 'draft');
        
        $data['meta_title'] = $request->input('meta_title') ?? $request->input('title');
        $data['meta_description'] = $request->input('meta_description') ?? Str::limit(strip_tags($request->input('content')), 150);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->storeAs('articles', $image->hashName(), 'public');
            $data['image'] = $image->hashName();
        }

        $article = Article::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Article Created Successfully',
            'data' => $article->load('category')
        ], 201);
    }

    public function show(Request $request, $id)
    {
        $article = Article::with(['category', 'author'])
            ->where('id', $id)
            ->when($request->published, function($query) use ($request) {
                return $query->where('published', $request->published);
            })
            ->firstOrFail();

        // Tambahkan flag untuk mengecek apakah user bisa edit
        $article->can_edit = auth()->check() && (
            auth()->id() === $article->author_id || 
            auth()->user()->role === 'direktur'
        );

        return response()->json([
            'success' => true,
            'message' => 'Detail Data Article',
            'data' => $article
        ], 200);
    }

    public function showPublic(Request $request, $slug)
    {
        $article = Article::with('category', 'author:id,name')
            ->where('slug', $slug)
            ->where('published', 'publish')
            ->firstOrFail();

        $ip = $request->ip();
        $cacheKey = 'article_view_' . $article->id . '_' . $ip;

        if (!\Illuminate\Support\Facades\Cache::has($cacheKey)) {
            $article->increment('total_view');
            \Illuminate\Support\Facades\Cache::put($cacheKey, true, now()->addMinutes(5));
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail Data Article',
            'data' => $article
        ], 200);
    }

    public function showPreview($slug)
    {
        // Ambil artikel beserta kategorinya berdasarkan slug
        $article = Article::with('category')
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'message' => 'Detail Preview Article',
            'data' => $article
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        // Cek apakah user adalah author dari artikel ini atau direktur
        $user = auth()->user();
        if ($article->author_id !== $user->id && $user->role !== 'direktur') {
            return response()->json([
                'success' => false,
                'message' => 'Anda hanya bisa mengedit artikel yang Anda buat sendiri'
            ], 403);
        }

        $request->validate([
            'category_id' => 'sometimes|required|exists:categories,id',
            'title' => 'sometimes|required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'content' => 'sometimes|required|string',
            'content_en' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'published' => 'nullable|in:draft,pending,publish'
        ]);

        $data = $request->all();

        if ($request->has('title')) {
            $data['slug'] = Str::slug($request->input('title'));
            $data['meta_title'] = $request->input('meta_title') ?? $request->input('title');
        }

        if ($request->has('content') && !$request->has('meta_description')) {
            $data['meta_description'] = Str::limit(strip_tags($request->input('content')), 150);
        }

        if ($request->hasFile('image')) {
            if ($article->image) {
                Storage::disk('public')->delete('articles/' . $article->image);
            }
            $image = $request->file('image');
            $image->storeAs('articles', $image->hashName(), 'public');
            $data['image'] = $image->hashName();
        }

        $article->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Article Updated Successfully',
            'data' => $article->load(['category', 'author'])
        ], 200);
    }

    public function approve($id)
    {
        $user = auth()->user();
        if ($user->role !== 'direktur') {
            return response()->json([
                'success' => false,
                'message' => 'Akses persetujuan hanya untuk Direktur'
            ], 403);
        }

        $article = Article::findOrFail($id);
        $article->update(['published' => 'publish']);

        return response()->json([
            'success' => true,
            'message' => 'Artikel berhasil disetujui',
            'data' => $article->load(['category', 'author'])
        ], 200);
    }

    public function destroy($id)
    {
        $article = Article::findOrFail($id);

        // Cek apakah user adalah author dari artikel ini atau direktur
        $user = auth()->user();
        if ($article->author_id !== $user->id && $user->role !== 'direktur') {
            return response()->json([
                'success' => false,
                'message' => 'Anda hanya bisa menghapus artikel yang Anda buat sendiri'
            ], 403);
        }

        if ($article->image) {
            Storage::disk('public')->delete('articles/' . $article->image);
        }
        $article->delete();
        return response()->json([
            'success' => true,
            'message' => 'Article Deleted Successfully'
        ], 200);
    }

    public function topByAuthor()
    {
        $currentMonth = \Carbon\Carbon::now()->month;
        $currentYear = \Carbon\Carbon::now()->year;

        $articles = Article::with('author:id,name')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->orderBy('total_view', 'desc')
            ->get();

        $authorsTopArticles = [];
        $authorIds = [];

        foreach ($articles as $article) {
            if (!in_array($article->author_id, $authorIds) && $article->author) {
                $authorIds[] = $article->author_id;
                $authorsTopArticles[] = $article;
            }

            if (count($authorsTopArticles) >= 3) {
                break;
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Top Articles By Author This Month',
            'data' => $authorsTopArticles
        ], 200);
    }
}