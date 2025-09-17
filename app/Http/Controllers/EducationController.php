<?php

namespace App\Http\Controllers;

use App\Models\EducationalModule;
use App\Models\EducationCategory;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class EducationController extends Controller
{
    public function index()
    {
        $materials = EducationalModule::with('category')
            ->where('category_id', '!=', 1) // Tidak menampilkan category_id = 1
            ->orderBy('id', 'desc')
            ->get();

        return view('education-index', [
            'title'      => 'Materi Edukasi',
            'list'       => $materials,
            'categories' => EducationCategory::where('id', '!=', 1) // Filter kategori juga
                ->orderBy('name', 'asc')
                ->get()
        ]);
    }

    public function create()
    {
        $materials = EducationalModule::with('category')
            ->orderBy('id', 'desc')
            ->get();

        return view('education-index', [
            'title'      => 'Materi Edukasi',
            'list'       => $materials,
            'categories' => EducationCategory::where('id', '!=', 1)
                ->orderBy('name', 'asc')
                ->get()
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

        return view('education-index', [
            'title' => 'Materi Edukasi',
            'data'  => $query,
            'categories' => EducationCategory::where('id', '!=', 1)
                ->orderBy('name', 'asc')
                ->get()
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

        $mediaType = $request->media_type;

        $request->validate([
            'title'        => 'required|string|max:255',
            'category_id'  => 'required|exists:module_categories,id',
            'media_type'   => 'required|in:image,video',
            'file_name'    => [
                (!$moduleId && $mediaType === 'image') ? 'required' : 'nullable',
                'file',
                'mimes:jpg,jpeg,png',
                'max:5120', // 5MB cukup untuk gambar
            ],
            'video_url'    => $mediaType === 'video' ? ['required', 'url'] : ['nullable'],
            'description'  => 'nullable|string',
        ], [
            'title.required'       => 'Judul materi wajib diisi.',
            'title.string'         => 'Judul materi harus berupa teks.',
            'title.max'            => 'Judul materi tidak boleh lebih dari :max karakter.',
            'category_id.required' => 'Kategori materi wajib dipilih.',
            'category_id.exists'   => 'Kategori materi tidak valid.',
            'media_type.required'  => 'Jenis media wajib dipilih.',
            'media_type.in'        => 'Jenis media tidak valid.',

            'file_name.required'   => 'Gambar wajib diupload.',
            'file_name.file'       => 'File tidak valid.',
            'file_name.mimes'      => 'Format file harus jpg, jpeg, atau png.',
            'file_name.max'        => 'Ukuran gambar maksimal 5 MB.',

            'video_url.required'   => 'Link YouTube wajib diisi.',
            'video_url.url'        => 'Link YouTube tidak valid.',
        ]);

        $module = $moduleId ? EducationalModule::findOrFail($moduleId) : new EducationalModule();

        $module->title       = $request->title;
        $module->category_id = $request->category_id;
        $module->media_type  = $mediaType;
        $module->description = $request->description;

        // Jika jenis media gambar
        if ($mediaType === 'image' && $request->hasFile('file_name')) {
            // Hapus file lama jika update
            if ($moduleId && $module->file_name) {
                $oldPath = 'uploads/modules/' . $module->file_name;
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            $extension = $request->file('file_name')->getClientOriginalExtension();
            $filename  = uniqid() . '.' . $extension;
            $request->file('file_name')->storeAs('uploads/modules', $filename, 'public');

            $module->file_name = $filename;
            $module->video_url = null; // pastikan kosong
        }

        // Jika jenis media link YouTube
        if ($mediaType === 'video') {
            // hapus file lama jika sebelumnya pernah upload gambar
            if ($module->file_name) {
                $oldPath = 'uploads/modules/' . $module->file_name;
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
                $module->file_name = null;
            }
            $module->video_url = $request->video_url;
        }

        $module->save();

        $message = $moduleId ? 'Materi berhasil diperbarui.' : 'Materi berhasil ditambahkan.';
        return redirect()->route('education.index')->with('success', $message);
    }

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

        if ($module->file_name) {
            $path = 'uploads/modules/' . $module->file_name;
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        $module->delete();
        return redirect()->route('education.index')->with('success', 'Materi berhasil dihapus.');
    }

    public function toggleVisibility($id)
    {
        try {
            $moduleId = Crypt::decrypt($id);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            return redirect()->back()->with('error', 'ID tidak valid.');
        }

        $module = EducationalModule::find($moduleId);

        if (!$module) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        $module->is_visible = !$module->is_visible;
        $module->save();

        $message = $module->is_visible ? 'Materi berhasil ditampilkan.' : 'Materi berhasil disembunyikan.';
        return redirect()->back()->with('success', $message);
    }
}
