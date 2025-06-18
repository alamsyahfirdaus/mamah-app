<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function show()
    {
        // Ambil data user yang sedang login
        $user = Auth::user();

        // Buat URL lengkap untuk file foto (jika ada)
        $user->photo = $user->photo
            ? URL::to('/') . '/storage/images/' . $user->photo
            : null;

        // Kembalikan data user dengan URL foto yang bisa ditampilkan langsung
        return response()->json([
            'message' => 'Data profil berhasil diambil.',
            'user'    => $user
        ]);
    }

    public function update(Request $request)
    {
        // Ambil user yang sedang login
        $user = Auth::user();

        // Validasi input dari request
        $request->validate([
            'name'     => 'nullable|string|max:255',
            'email'    => 'nullable|email|max:255|unique:users,email,' . $user->id,
            'phone'    => 'nullable|string|max:20',
            'address'  => 'nullable|string|max:255',
            'photo'    => 'nullable|file|image|mimes:jpeg,png,jpg|max:2048',
            'password' => 'nullable|string|min:6',
        ], [
            'name.string'     => 'Nama harus berupa teks.',
            'name.max'        => 'Nama tidak boleh lebih dari 255 karakter.',
            'email.email'     => 'Format email tidak valid.',
            'email.max'       => 'Email tidak boleh lebih dari 255 karakter.',
            'email.unique'    => 'Email ini sudah digunakan oleh pengguna lain.',
            'phone.string'    => 'Nomor HP harus berupa teks.',
            'phone.max'       => 'Nomor HP tidak boleh lebih dari 20 karakter.',
            'address.string'  => 'Alamat harus berupa teks.',
            'address.max'     => 'Alamat tidak boleh lebih dari 255 karakter.',
            'photo.file'      => 'Foto harus berupa file.',
            'photo.image'     => 'Foto harus berupa gambar.',
            'photo.mimes'     => 'Foto harus berformat jpeg, png, atau jpg.',
            'photo.max'       => 'Ukuran foto maksimal 2MB.',
            'password.min'    => 'Password minimal terdiri dari 6 karakter.',
        ]);

        // Inisialisasi variabel untuk nama file baru
        $photoFileName = $user->photo;

        // Jika ada file foto baru diunggah
        if ($request->hasFile('photo')) {
            // Hapus file lama jika ada dan tersimpan di storage
            if ($user->photo && Storage::disk('public')->exists('images/' . basename($user->photo))) {
                Storage::disk('public')->delete('images/' . basename($user->photo));
            }

            // Simpan file baru dengan nama acak
            $extension = $request->file('photo')->getClientOriginalExtension();
            $newFileName = Str::random(20) . '.' . $extension;
            $request->file('photo')->storeAs('images', $newFileName, 'public');

            // Simpan nama file (tanpa 'storage/')
            $photoFileName = 'images/' . $newFileName;
        }

        // Update data pengguna
        $user->update([
            'name'     => $request->name ?? $user->name,
            'email'    => $request->email ?? $user->email,
            'phone'    => $request->phone ?? $user->phone,
            'address'  => $request->address ?? $user->address,
            'photo'    => $photoFileName,
            'password' => $request->filled('password') ? Hash::make($request->password) : $user->password,
        ]);

        // Buat URL penuh untuk foto
        $user->photo = $user->photo ? url('storage/' . $user->photo) : null;

        return response()->json([
            'message' => 'Profil berhasil diperbarui.',
            'user'    => $user
        ]);
    }
}
