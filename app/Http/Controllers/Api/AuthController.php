<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // Proses login pengguna
    public function login(Request $request)
    {
        // Validasi input login
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // Cari user berdasarkan email
        $user = User::where('email', $request->email)->first();

        // Cek apakah user ditemukan dan password cocok
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Login gagal. Email atau kata sandi salah.'
            ], 401);
        }

        // Buat token autentikasi
        $token = $user->createToken('mobile-token')->plainTextToken;

        // Kirim token dan data user
        return response()->json([
            'access_token' => $token,
            'user' => $user
        ]);
    }

    // Proses logout pengguna
    public function logout(Request $request)
    {
        // Pastikan user valid
        if (!$request->user()) {
            return response()->json([
                'message' => 'Token tidak valid atau sudah kedaluwarsa. Silakan login kembali.'
            ], 401);
        }

        // Hapus token akses saat ini
        $request->user()->currentAccessToken()->delete();

        // Kirim respon berhasil
        return response()->json([
            'message' => 'Logout berhasil.'
        ]);
    }

    public function register(Request $request)
    {
        // Validasi input dari pengguna saat registrasi
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role'     => 'required|in:ibu,bidan',
            'photo'    => 'nullable|file|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'name.required'     => 'Nama wajib diisi.',
            'email.required'    => 'Email wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
            'role.required'     => 'Peran pengguna wajib dipilih.',
            'photo.file'        => 'Foto harus berupa file.',
            'photo.image'       => 'Foto harus berupa gambar.',
            'photo.mimes'       => 'Format foto harus jpeg, jpg, atau png.',
            'photo.max'         => 'Ukuran foto maksimal 2MB.',
        ]);

        // Proses simpan file foto
        $photoFilename = null;
        if ($request->hasFile('photo')) {
            // Generate nama file unik
            $extension = $request->file('photo')->getClientOriginalExtension();
            $photoFilename = Str::random(20) . '.' . $extension;

            // Simpan file ke folder public/images
            $request->file('photo')->storeAs('images', $photoFilename, 'public');
        }

        // Buat user baru
        $user = User::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'role'        => $request->role,
            'is_verified' => 1,
            'photo'       => $photoFilename, // Hanya nama file yang disimpan di DB
        ]);

        // Buat token autentikasi
        $token = $user->createToken('mobile-token')->plainTextToken;

        // Kirim respon registrasi
        return response()->json([
            'message'      => 'Registrasi berhasil.',
            'access_token' => $token,
            'user'         => [
                'id'          => $user->id,
                'name'        => $user->name,
                'email'       => $user->email,
                'role'        => $user->role,
                'phone'       => $user->phone,
                'address'     => $user->address,
                'photo'       => $user->photo
                    ? url('storage/images/' . $user->photo)
                    : null,
                'is_verified' => $user->is_verified,
                'created_at'  => $user->created_at,
                'updated_at'  => $user->updated_at,
            ]
        ], 201);
    }
}
