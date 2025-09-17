<?php

namespace App\Http\Controllers;

use App\Models\EducationalModule;
use App\Models\EducationCategory;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class RelaxationController extends Controller
{
    public function index()
    {
        $materials = EducationalModule::where('category_id', 1)
            ->orderBy('id', 'desc')
            ->get();

        return view('relaxation-index', [
            'title' => 'Materi Relaksasi',
            'list'  => $materials,
        ]);
    }

    public function create()
    {
        return view('relaxation-index', [
            'title' => 'Materi Relaksasi',
        ]);
    }

    public function edit($id)
    {
        try {
            $decryptId = Crypt::decrypt($id);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            return redirect()->back()->with('error', 'ID tidak valid.');
        }

        $query = EducationalModule::find($decryptId);

        if (!$query) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        return view('relaxation-index', [
            'title' => 'Materi Relaksasi',
            'data'  => $query,
        ]);
    }

    public function store(Request $request)
    {
        $moduleId = null;

        // Jika ada input 'id', berarti update
        if ($request->filled('id')) {
            try {
                $moduleId = Crypt::decrypt($request->id);
            } catch (DecryptException $e) {
                return redirect()->back()->with('error', 'ID tidak valid.');
            }
        }

        // Validasi input
        $request->validate([
            'title'      => 'required|string|max:255',
            'video_url'  => 'required|url',
            'description' => 'nullable|string',
        ], [
            'title.required'     => 'Judul relaksasi wajib diisi.',
            'title.string'       => 'Judul relaksasi harus berupa teks.',
            'title.max'          => 'Judul relaksasi tidak boleh lebih dari :max karakter.',
            'video_url.required' => 'Link YouTube wajib diisi.',
            'video_url.url'      => 'Link YouTube tidak valid.',
        ]);

        // Ambil atau buat data baru
        $module = $moduleId ? EducationalModule::findOrFail($moduleId) : new EducationalModule();

        $module->title       = $request->title;
        $module->category_id = 1; // Kategori relaksasi selalu 1
        $module->media_type  = 'video'; // Hanya link YouTube
        $module->video_url   = $request->video_url;
        $module->description = $request->description;

        $module->save();

        $message = $moduleId ? 'Relaksasi berhasil diperbarui.' : 'Relaksasi berhasil ditambahkan.';
        return redirect()->route('relaxation.index')->with('success', $message);
    }

    // Hapus relaksasi
    public function destroy(string $id)
    {
        try {
            $moduleId = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'ID tidak valid.');
        }

        $module = EducationalModule::find($moduleId);
        if (!$module) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        // Hapus data
        $module->delete();

        return redirect()->route('relaxation.index')->with('success', 'Relaksasi berhasil dihapus.');
    }

    // Toggle visibility (tampil/ sembunyikan)
    public function toggleVisibility(string $id)
    {
        try {
            $moduleId = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'ID tidak valid.');
        }

        $module = EducationalModule::find($moduleId);
        if (!$module) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        $module->is_visible = !$module->is_visible;
        $module->save();

        $message = $module->is_visible ? 'Relaksasi berhasil ditampilkan.' : 'Relaksasi berhasil disembunyikan.';
        return redirect()->back()->with('success', $message);
    }
}
