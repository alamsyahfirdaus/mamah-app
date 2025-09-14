<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show()
    {
        // Ambil data user login beserta relasi district → city → province
        $user = Auth::user()->load('district.city.province');

        // Buat URL lengkap untuk file foto (jika ada)
        $user->photo = $user->photo
            ? URL::to('/') . '/storage/' . $user->photo
            : null;

        // Siapkan nama lokasi lengkap dalam satu baris: Kecamatan, Kota, Provinsi
        $districtName = null;
        if ($user->district) {
            $regionParts = [
                $user->district->name ?? null,
                $user->district->city->name ?? null,
                $user->district->city->province->name ?? null
            ];
            $districtName = implode(', ', array_filter($regionParts));
        }

        // Konversi user menjadi array dan tambahkan field district_name
        $userData = $user->toArray();
        $userData['district_name'] = $districtName;

        // Kembalikan data user
        return response()->json([
            'message' => 'Data profil berhasil diambil.',
            'user'    => $userData,
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user(); // Menggunakan $request->user() agar konsisten dengan Laravel Sanctum

        // Validasi input
        $validated = $request->validate([
            'name'     => 'nullable|string|max:255',
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone'    => 'nullable|string|max:20',
            'address'  => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'password' => 'nullable|string|min:6',
            'photo'    => 'nullable|file|image|mimes:jpeg,png,jpg|max:2048',
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
            'birth_date.date' => 'Tanggal lahir harus format tanggal.',
        ]);

        // Siapkan path foto
        $photoFileName = $user->photo;

        // Jika upload foto baru
        if ($request->hasFile('photo')) {
            // Hapus file lama jika ada
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }

            // Simpan foto baru
            $extension = $request->file('photo')->getClientOriginalExtension();
            $newFileName = Str::random(20) . '.' . $extension;
            $request->file('photo')->storeAs('images', $newFileName, 'public');
            $photoFileName = 'images/' . $newFileName;
        }

        // Siapkan data untuk update
        $dataToUpdate = [
            'name'       => $request->name ?? $user->name,
            'email'      => $request->email ?? $user->email,
            'phone'      => $request->phone ?? $user->phone,
            'address'    => $request->address ?? $user->address,
            'birth_date' => $request->birth_date ?? $user->birth_date,
            'photo'      => $photoFileName,
        ];

        // Jika ada password baru
        if ($request->filled('password')) {
            $dataToUpdate['password'] = Hash::make($request->password);
        }

        // Proses update
        try {
            $user->update($dataToUpdate);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal memperbarui profil.',
                'error'   => $e->getMessage(),
            ], 500);
        }

        // Generate URL foto lengkap
        $user->photo = $user->photo ? url('storage/' . $user->photo) : null;

        return response()->json([
            'status'  => 'success',
            'message' => 'Profil berhasil diperbarui.',
            'data'    => [
                'profile' => $user,
            ]
        ]);
    }
}
