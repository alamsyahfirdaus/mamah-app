<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DistrictModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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
        // Validasi input awal (tampilan pertama)
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|string|email|max:255|unique:users',
            'password'    => 'required|string|min:6',
            'role'        => 'required|in:ibu,bidan',
            'district_id' => 'required|exists:districts,id', // validasi kecamatan
        ], [
            'name.required'        => 'Nama wajib diisi.',
            'email.required'       => 'Email wajib diisi.',
            'email.unique'         => 'Email sudah digunakan.',
            'password.required'    => 'Kata sandi wajib diisi.',
            'role.required'        => 'Peran pengguna wajib dipilih.',
            'district_id.required' => 'Kecamatan wajib dipilih.',
            'district_id.exists'   => 'Kecamatan tidak valid.',
        ]);

        // Buat user baru
        $user = User::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'role'        => $request->role,
            'district_id' => $request->district_id, // simpan district_id
            'is_verified' => 1,
        ]);

        // Buat token autentikasi Sanctum
        $token = $user->createToken('mobile-token')->plainTextToken;

        // Kirim respon
        return response()->json([
            'message'      => 'Registrasi berhasil.',
            'access_token' => $token,
            'user'         => [
                'id'          => $user->id,
                'name'        => $user->name,
                'email'       => $user->email,
                'role'        => $user->role,
                'district_id' => $user->district_id,
                'phone'       => null,
                'address'     => null,
                'photo'       => null,
                'is_verified' => $user->is_verified,
                'created_at'  => $user->created_at,
                'updated_at'  => $user->updated_at,
            ]
        ], 201);
    }

    public function completeProfile(Request $request)
    {
        $user = $request->user();

        // Validasi input dengan pesan khusus bahasa Indonesia
        $request->validate([
            'phone'       => 'nullable|string|max:20',
            'address'     => 'nullable|string|max:255',
            'birth_date'  => 'nullable|date',
            'photo'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'phone.string'        => 'Nomor telepon harus berupa teks.',
            'phone.max'           => 'Nomor telepon maksimal 20 karakter.',
            'address.string'      => 'Alamat harus berupa teks.',
            'address.max'         => 'Alamat maksimal 255 karakter.',
            'birth_date.date'     => 'Tanggal lahir tidak valid.',
            'photo.image'         => 'File foto harus berupa gambar.',
            'photo.mimes'         => 'Format foto harus JPEG, PNG, atau JPG.',
            'photo.max'           => 'Ukuran foto maksimal 2 MB.',
        ]);

        // Handle upload foto jika ada
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }

            // Simpan foto baru
            $filename = Str::random(20) . '.' . $request->file('photo')->getClientOriginalExtension();
            $path = $request->file('photo')->storeAs('images', $filename, 'public');
            $user->photo = $path;
        }

        // Update field profil
        $user->phone       = $request->phone ?? $user->phone;
        $user->address     = $request->address ?? $user->address;
        $user->birth_date  = $request->birth_date ?? $user->birth_date;

        $user->save();

        // Format tanggal konsisten di response JSON
        $formatDate = fn($date) => $date ? $date->format('Y-m-d') : null;

        return response()->json([
            'message' => 'Profil berhasil dilengkapi.',
            'user'    => [
                'id'          => $user->id,
                'name'        => $user->name,
                'email'       => $user->email,
                'role'        => $user->role,
                'district_id' => $user->district_id,
                'phone'       => $user->phone,
                'address'     => $user->address,
                'birth_date'  => $formatDate($user->birth_date),
                'photo'       => $user->photo ? url('storage/' . $user->photo) : null,
                'created_at'  => $formatDate($user->created_at),
                'updated_at'  => $formatDate($user->updated_at),
            ]
        ]);
    }

    // Fungsi untuk menampilkan daftar region (kecamatan → kota → provinsi)
    public function getDistricts()
    {
        // Ambil semua kecamatan beserta relasi kota dan provinsi
        $districts = DistrictModel::with('city.province')
            ->orderBy('name', 'asc') // Urutkan berdasarkan nama kecamatan
            ->get();

        // Format data menjadi array untuk API
        $regions = $districts->map(function ($district) {
            return [
                'id'   => $district->id,
                'name' => trim("{$district->name}, {$district->city->name}, {$district->city->province->name}", ', '),
            ];
        });

        // Kirim response JSON
        return response()->json([
            'message' => 'Daftar kecamatan berhasil diambil.',
            'data'    => $regions,
        ]);
    }
}
