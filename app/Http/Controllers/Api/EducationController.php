<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EducationalModule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class EducationController extends Controller
{
    // Menampilkan semua materi edukasi yang 'is_visible' = true
    public function index()
    {
        // Ambil semua modul edukasi yang ditandai tampil, urut terbaru
        $modules = EducationalModule::where('is_visible', true)
            ->orderBy('created_at', 'desc')
            ->get();

        // Jika tidak ada modul edukasi
        if ($modules->isEmpty()) {
            return response()->json([
                'message' => 'Belum ada materi edukasi yang tersedia.',
                'data' => []
            ], 404);
        }

        // Format data modul untuk ditampilkan di aplikasi mobile
        $data = $modules->map(function ($module) {
            return [
                'id'          => $module->id,
                'title'       => $module->title,
                'image_url'   => $module->image
                    ? URL::to('/') . '/storage/images/' . $module->image // URL lengkap gambar
                    : null,
                'video_url'   => $module->video_url,
                'category_id' => $module->category_id,
                'created_at'  => $module->created_at->format('Y-m-d'),
            ];
        });

        // Kirim response sukses dengan data
        return response()->json([
            'message' => 'Daftar materi edukasi berhasil diambil.',
            'data'    => $data
        ]);
    }

    // Menampilkan detail modul edukasi berdasarkan ID
    public function show($id)
    {
        // Cari modul berdasarkan ID dan hanya yang visible
        $module = EducationalModule::where('id', $id)
            ->where('is_visible', true)
            ->first();

        // Jika tidak ditemukan
        if (!$module) {
            return response()->json([
                'message' => 'Materi edukasi tidak ditemukan.'
            ], 404);
        }

        // Kembalikan detail modul
        return response()->json([
            'message' => 'Detail materi edukasi berhasil diambil.',
            'data' => [
                'id'          => $module->id,
                'title'       => $module->title,
                'content'     => $module->content,
                'image_url'   => $module->image
                    ? URL::to('/') . '/storage/images/' . $module->image
                    : null,
                'video_url'   => $module->video_url,
                'category_id' => $module->category_id,
                'created_at'  => $module->created_at->format('Y-m-d'),
                'updated_at'  => $module->updated_at->format('Y-m-d'),
            ]
        ]);
    }

    // Menampilkan 1 materi edukasi terbaru
    public function latest()
    {
        // Ambil 1 modul edukasi paling baru dan visible
        $module = EducationalModule::where('is_visible', true)
            ->orderBy('created_at', 'desc')
            ->first();

        // Jika tidak ada data sama sekali
        if (!$module) {
            return response()->json([
                'message' => 'Belum ada materi edukasi terbaru yang tersedia.',
                'data' => null
            ], 404);
        }

        // Kembalikan data modul terbaru
        return response()->json([
            'message' => 'Materi edukasi terbaru berhasil diambil.',
            'data' => [
                'id'          => $module->id,
                'title'       => $module->title,
                'content'     => $module->content,
                'image_url'   => $module->image
                    ? URL::to('/') . '/storage/images/' . $module->image
                    : null,
                'video_url'   => $module->video_url,
                'category_id' => $module->category_id,
                'created_at'  => $module->created_at->format('Y-m-d'),
                'updated_at'  => $module->updated_at->format('Y-m-d'),
            ]
        ]);
    }
}
