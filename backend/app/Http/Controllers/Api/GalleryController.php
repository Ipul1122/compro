<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    // Tampilkan semua data gallery (bisa dipanggil publik jika dibutuhkan)
    public function index()
    {
        $galleries = Gallery::with('category')->latest()->get();
        
        return response()->json([
            'status' => 'success',
            'data' => $galleries
        ]);
    }

    // Simpan data gallery (Admin)
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

    // Detail gallery (Admin)
    public function show(Gallery $gallery)
    {
        return response()->json([
            'status' => 'success',
            'data' => $gallery->load('category')
        ]);
    }

    // Update data gallery (Admin)
    public function update(Request $request, Gallery $gallery)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title_image' => 'required|string|max:255',
            'meta_title_image' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $data = $request->only(['category_id', 'title_image', 'meta_title_image']);

        // Jika ada file image baru yang diupload
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($gallery->image && Storage::disk('public')->exists($gallery->image)) {
                Storage::disk('public')->delete($gallery->image);
            }
            
            // Simpan gambar baru
            $data['image'] = $request->file('image')->store('galleries', 'public');
        }

        $gallery->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Gallery berhasil diupdate',
            'data' => $gallery
        ]);
    }

    // Hapus gallery (Admin)
    public function destroy(Gallery $gallery)
    {
        // Hapus gambar dari storage
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
            'title_image' => 'nullable|string|max:255', // Opsional, bisa pakai nama file asli jika kosong
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
            // Hapus file fisik
            if ($gallery->image && Storage::disk('public')->exists($gallery->image)) {
                Storage::disk('public')->delete($gallery->image);
            }
            // Hapus record database
            $gallery->delete();
        }

        return response()->json([
            'status'  => 'success',
            'message' => count($galleries) . ' item berhasil dihapus.'
        ]);
    }
}
