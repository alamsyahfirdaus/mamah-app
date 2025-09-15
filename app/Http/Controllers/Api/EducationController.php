<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EducationalModule;
use App\Models\EducationCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class EducationController extends Controller
{
    public function index()
    {
        //
    }

    /**
     * List semua materi edukasi, dengan filter optional berdasarkan category_id.
     * Bisa diakses via GET (query parameter) atau POST (body).
     */
    public function listEducationalModules(Request $request)
    {
        // Ambil category_id dari query parameter (GET) atau body (POST)
        $categoryId = $request->query('category_id') ?? $request->input('category_id');

        // Buat query dasar: hanya modul yang visible
        $query = EducationalModule::where('is_visible', true);

        // Jika category_id dikirim, tambahkan filter
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        // Ambil semua data sesuai query, urut berdasarkan tanggal terbaru
        $modules = $query->orderBy('created_at', 'desc')->get();

        // Jika tidak ada modul edukasi
        if ($modules->isEmpty()) {
            return response()->json([
                'message' => 'Belum ada materi edukasi yang tersedia.',
                'data'    => []
            ], 404);
        }

        // Format data modul agar konsisten untuk aplikasi mobile
        $data = $modules->map(function ($module) {
            return [
                'id'          => $module->id,
                'title'       => $module->title,
                'media_type'  => $module->media_type,
                'file_url'    => $module->file_name
                    ? URL::to('/') . '/storage/uploads/modules/' . $module->file_name
                    : null,
                'description' => $module->description,
                'category_id' => $module->category_id,
                'created_at'  => $module->created_at->format('Y-m-d'),
            ];
        });

        // Kembalikan response JSON
        return response()->json([
            'message' => 'Daftar materi edukasi berhasil diambil.',
            'data'    => $data
        ]);
    }

    /**
     * Menampilkan detail modul edukasi berdasarkan ID
     */
    public function show($id)
    {
        // Cari modul berdasarkan ID dan hanya yang visible
        $module = EducationalModule::where('id', $id)
            ->where('is_visible', true)
            ->first();

        // Jika modul tidak ditemukan
        if (!$module) {
            return response()->json([
                'message' => 'Materi edukasi tidak ditemukan.'
            ], 404);
        }

        // Format data modul dan kembalikan response JSON
        return response()->json([
            'message' => 'Detail materi edukasi berhasil diambil.',
            'data' => [
                'id'          => $module->id,
                'title'       => $module->title,
                'media_type'  => $module->media_type,
                'file_url'    => $module->file_name
                    ? URL::to('/') . '/storage/uploads/modules/' . $module->file_name
                    : null,
                'description' => $module->description,
                'category_id' => $module->category_id,
                'created_at'  => $module->created_at->format('Y-m-d'),
                'updated_at'  => $module->updated_at->format('Y-m-d'),
            ]
        ]);
    }

    /**
     * Menampilkan 1 materi edukasi terbaru
     */
    public function latest()
    {
        // Ambil modul terbaru yang visible
        $module = EducationalModule::where('is_visible', true)
            ->orderBy('created_at', 'desc')
            ->first();

        // Jika tidak ada modul
        if (!$module) {
            return response()->json([
                'message' => 'Belum ada materi edukasi terbaru yang tersedia.',
                'data'    => null
            ], 404);
        }

        // Format data modul terbaru dan kembalikan response JSON
        return response()->json([
            'message' => 'Materi edukasi terbaru berhasil diambil.',
            'data' => [
                'id'          => $module->id,
                'title'       => $module->title,
                'media_type'  => $module->media_type,
                'file_url'    => $module->file_name
                    ? URL::to('/') . '/storage/uploads/modules/' . $module->file_name
                    : null,
                'description' => $module->description,
                'category_id' => $module->category_id,
                'created_at'  => $module->created_at->format('Y-m-d'),
                'updated_at'  => $module->updated_at->format('Y-m-d'),
            ]
        ]);
    }

    /**
     * Menampilkan daftar kategori materi edukasi
     * Hanya menampilkan id dan name
     */
    public function listCategories()
    {
        // Ambil semua kategori, urut berdasarkan nama ascending
        $categories = EducationCategory::orderBy('name', 'asc')->get();

        // Format data kategori
        $data = $categories->map(function ($category) {
            return [
                'id'   => $category->id,
                'name' => $category->name,
            ];
        });

        // Kembalikan response JSON
        return response()->json([
            'message' => 'Daftar kategori materi berhasil diambil.',
            'data'    => $data
        ], 200);
    }
}
