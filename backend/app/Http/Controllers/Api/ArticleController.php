<?php

namespace App\Http\Controllers\Api; // Sesuaikan namespace jika ada di folder Api

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        // 1. Optimasi: Pilih kolom yang diperlukan saja (Hide 'content' agar query lebih ringan)
        $query = Article::select('id', 'category_id', 'title', 'slug', 'image', 'published', 'total_view', 'created_at')
            // 2. Optimasi: Eager load category dan ambil kolom nama & id saja dari tabel category
            ->with('category:id,name,slug') 
            ->latest();

        // Filter berdasarkan status
        if ($request->has('published')) {
            $query->where('published', $request->published);
        }

        // 3. Pagination: 10 item per halaman
        $articles = $query->paginate(10);

        // Custom struktur JSON agar rapi antara data dan meta-pagination
        return response()->json([
            'success' => true,
            'message' => 'List Data Articles',
            'data'    => $articles->items(), // Mengambil array data utamanya saja
            'pagination' => [
                'total'        => $articles->total(), // Total seluruh data di database
                'per_page'     => $articles->perPage(), // Jumlah data per halaman (10)
                'current_page' => $articles->currentPage(), // Halaman saat ini
                'last_page'    => $articles->lastPage(), // Halaman terakhir
                'next_page_url'=> $articles->nextPageUrl(), // Link URL untuk halaman selanjutnya
                'prev_page_url'=> $articles->previousPageUrl(), // Link URL untuk halaman sebelumnya
            ]
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'published' => 'nullable|in:draft,publish'
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->input('title'));
        
        // Atur default meta jika kosong
        $data['meta_title'] = $request->input('meta_title') ?? $request->input('title');
        $data['meta_description'] = $request->input('meta_description') ?? Str::limit(strip_tags($request->input('content')), 150);

        // Upload Image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->storeAs('public/articles', $image->hashName());
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
            $article = Article::with('category')
                ->where('id', $id)
                // Jika ada query ?published=... di URL, maka difilter. 
                // Kalau gak ada, dia bakal cari ID 1 apa adanya.
                ->when($request->published, function($query) use ($request) {
                    return $query->where('published', $request->published);
                })
                ->firstOrFail(); // Pake firstOrFail biar balikannya 404 kalau status gak cocok

            return response()->json([
                'success' => true,
                'message' => 'Detail Data Article',
                'data' => $article
            ], 200);
        }

    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        $request->validate([
            'category_id' => 'sometimes|required|exists:categories,id',
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'published' => 'nullable|in:draft,publish'
        ]);

        $data = $request->all();

        // Update slug jika title berubah
        if ($request->has('title')) {
            $data['slug'] = Str::slug($request->input('title'));
            $data['meta_title'] = $request->input('meta_title') ?? $request->input('title');
        }

        if ($request->has('content') && !$request->has('meta_description')) {
            $data['meta_description'] = Str::limit(strip_tags($request->input('content')), 150);
        }

        // Handle Image Update
        if ($request->hasFile('image')) {
            // Hapus gambar lama
            if ($article->image) {
                Storage::delete('public/articles/' . $article->image);
            }
            // Upload gambar baru
            $image = $request->file('image');
            $image->storeAs('public/articles', $image->hashName());
            $data['image'] = $image->hashName();
        }

        $article->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Article Updated Successfully',
            'data' => $article->load('category')
        ], 200);
    }

    public function destroy($id)
    {
        $article = Article::findOrFail($id);

        // Hapus gambar
        if ($article->image) {
            Storage::delete('public/articles/' . $article->image);
        }

        $article->delete();

        return response()->json([
            'success' => true,
            'message' => 'Article Deleted Successfully'
        ], 200);
    }
}