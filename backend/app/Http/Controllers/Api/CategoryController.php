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
        $query = Category::select('id', 'name', 'slug', 'user_id', 'created_at')
            ->with('creator:id,name')
            ->withCount(['articles', 'galleries'])
            ->latest();

        // 🔍 FITUR PENCARIAN (Berdasarkan Nama Kategori)
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $categories = $query->paginate(10);

        // Tambahkan info creator_name ke setiap item
        $items = collect($categories->items())->map(function ($cat) {
            return [
                'id'             => $cat->id,
                'name'           => $cat->name,
                'slug'           => $cat->slug,
                'user_id'        => $cat->user_id,
                'creator_name'   => $cat->creator?->name ?? 'Tidak diketahui',
                'articles_count' => $cat->articles_count,
                'galleries_count'=> $cat->galleries_count,
                'created_at'     => $cat->created_at,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'List Data Categories',
            'data'    => $items,
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
            'user_id' => auth()->id(), // Simpan siapa yang membuat
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

        $user = auth()->user();
        // Hanya pemilik, admin, atau direktur yang boleh edit
        if ($category->user_id && $category->user_id != $user->id && $user->role !== 'direktur' && $user->role !== 'admin') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Anda tidak memiliki izin untuk mengedit kategori ini.'
            ], 403);
        }

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

        $user = auth()->user();
        // Hanya pemilik, admin, atau direktur yang boleh hapus
        if ($category->user_id && $category->user_id != $user->id && $user->role !== 'direktur' && $user->role !== 'admin') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Anda tidak memiliki izin untuk menghapus kategori ini.'
            ], 403);
        }

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