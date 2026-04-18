<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $query = Gallery::select('id', 'category_id', 'title_image', 'meta_title_image', 'image', 'created_at', 'updated_at')
            ->with('category:id,name,slug')
            ->latest();

        // Filter berdasarkan kategori
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Fitur Pencarian Judul
        if ($request->filled('search')) {
            $query->where('title_image', 'like', '%' . $request->search . '%');
        }

        // PENTING: Kita menggunakan get() BUKAN paginate(10) di sini.
        // Jika menggunakan paginate(10), gambar di dalam satu "Wadah" yang sama 
        // akan terpotong dan terbelah menjadi 2 halaman di Frontend.
        // Paginasi dan sinkronisasi URL (?page=2) akan di-handle sepenuhnya oleh Vue Router.
        $galleries = $query->get();

        return response()->json([
            'status' => 'success',
            'message' => 'List Data Galleries',
            'data' => $galleries
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title_image' => 'required|string|max:255',
            'meta_title_image' => 'nullable|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $imagePath = $request->file('image')->store('galleries', 'public');

        $gallery = Gallery::create([
            'category_id' => $request->category_id,
            'title_image' => $request->title_image,
            'meta_title_image' => $request->meta_title_image,
            'image' => $imagePath
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Gallery berhasil ditambahkan',
            'data' => $gallery
        ], 201);
    }

    public function show(Gallery $gallery)
    {
        return response()->json([
            'status' => 'success',
            'data' => $gallery->load('category')
        ]);
    }

    public function update(Request $request, Gallery $gallery)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title_image' => 'required|string|max:255',
            'meta_title_image' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $data = $request->only(['category_id', 'title_image', 'meta_title_image']);

        if ($request->hasFile('image')) {
            if ($gallery->image && Storage::disk('public')->exists($gallery->image)) {
                Storage::disk('public')->delete($gallery->image);
            }
            $data['image'] = $request->file('image')->store('galleries', 'public');
        }

        $gallery->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Gallery berhasil diupdate',
            'data' => $gallery
        ]);
    }

    public function destroy(Gallery $gallery)
    {
        if ($gallery->image && Storage::disk('public')->exists($gallery->image)) {
            Storage::disk('public')->delete($gallery->image);
        }

        $gallery->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Gallery berhasil dihapus'
        ]);
    }

    public function bulkStore(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'images'      => 'required|array',
            'images.*'    => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'title_image' => 'nullable|string|max:255',
        ]);

        $uploadedData = [];
        $categoryId = $request->category_id;
        $titleBase = $request->title_image ?? 'Gallery';

        foreach ($request->file('images') as $index => $image) {
            $path = $image->store('galleries', 'public');
            
            $gallery = Gallery::create([
                'category_id'      => $categoryId,
                'title_image'      => $titleBase . " " . ($index + 1),
                'image'            => $path,
                'meta_title_image' => $titleBase . " " . ($index + 1),
            ]);

            $uploadedData[] = $gallery;
        }

        return response()->json([
            'status'  => 'success',
            'message' => count($uploadedData) . ' gambar berhasil diupload.',
            'data'    => $uploadedData
        ], 201);
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'exists:galleries,id'
        ]);

        $galleries = Gallery::whereIn('id', $request->ids)->get();

        foreach ($galleries as $gallery) {
            if ($gallery->image && Storage::disk('public')->exists($gallery->image)) {
                Storage::disk('public')->delete($gallery->image);
            }
            $gallery->delete();
        }

        return response()->json([
            'status'  => 'success',
            'message' => count($galleries) . ' item berhasil dihapus.'
        ]);
    }
}