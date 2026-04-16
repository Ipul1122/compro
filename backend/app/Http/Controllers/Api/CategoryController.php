<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::select('id', 'name', 'slug', 'created_at')
            ->withCount('articles')
            ->latest();

        // 🔍 FITUR PENCARIAN (Berdasarkan Nama Kategori)
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $categories = $query->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'List Data Categories',
            'data'    => $categories->items(),
            'pagination' => [
                'total'        => $categories->total(),
                'per_page'     => $categories->perPage(),
                'current_page' => $categories->currentPage(),
                'last_page'    => $categories->lastPage(),
            ]
        ], 200);
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string'
        ]);

        $category = Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'meta_title' => $request->meta_title ?? $request->name,
            'meta_description' => $request->meta_description ?? 'Kumpulan artikel dan informasi terbaru seputar kategori ' . $request->name,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Kategori berhasil ditambahkan',
            'data' => $category
        ], 201);
    }

    public function show($id)
    {
        $category = Category::findOrFail($id);
        return response()->json([
            'status' => 'success',
            'data' => $category
        ]);
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string'
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'meta_title' => $request->meta_title ?? $request->name,
            'meta_description' => $request->meta_description ?? 'Kumpulan artikel dan informasi terbaru seputar kategori ' . $request->name,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Kategori berhasil diperbarui',
            'data' => $category
        ]);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Kategori berhasil dihapus'
        ]);
    }

    // Tambahkan fungsi baru ini
    public function list()
    {
        // Hanya ambil ID dan Name, tidak perlu pagination dan withCount
        $categories = Category::select('id', 'name')->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Data Kategori untuk Form',
            'data'    => $categories
        ], 200);
    }
}