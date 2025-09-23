<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PregnantMother;
use App\Models\User;
use App\Models\VillageModel;
use Carbon\Carbon;
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
        // Validasi input awal
        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|string|email|max:255|unique:users',
            'password'   => 'required|string|min:6',
            'role'       => 'required|in:ibu,bidan',
            'village_id' => 'required|exists:villages,id',
        ], [
            'name.required'       => 'Nama wajib diisi.',
            'email.required'      => 'Email wajib diisi.',
            'email.unique'        => 'Email sudah digunakan.',
            'password.required'   => 'Kata sandi wajib diisi.',
            'role.required'       => 'Peran pengguna wajib dipilih.',
            'village_id.required' => 'Desa/Kelurahan wajib dipilih.',
            'village_id.exists'   => 'Desa/Kelurahan tidak valid.',
        ]);

        // Buat user baru
        $user = User::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'role'        => $request->role,
            'village_id'  => $request->village_id,
            'is_verified' => 1,
        ]);

        // Jika role = ibu, tambahkan data ke pregnant_mothers
        if ($user->role == 'ibu') {
            PregnantMother::create([
                'user_id'             => $user->id,
                'mother_age'          => 0,
                'pregnancy_number'    => 0,
                'live_children_count' => 0,
                'miscarriage_history' => 0,
                'mother_disease_history' => "tidak ada",
            ]);
        }

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
                'village_id'  => $user->village_id,
                'phone'       => null,
                'address'     => null,
                'photo'       => null,
                'is_verified' => $user->is_verified,
                'created_at'  => $user->created_at,
                'updated_at'  => $user->updated_at,
            ]
        ], 201);
    }

    public function completeMotherData(Request $request)
    {
        // 6️⃣ Hanya user yang login (dengan token) yang bisa akses
        $user = $request->user();

        if ($user->role !== 'ibu') {
            return response()->json([
                'message' => 'Hanya pengguna dengan peran ibu yang bisa melengkapi data.'
            ], 403);
        }

        // 7️⃣ Validasi input tambahan
        $request->validate([
            'mother_age'             => 'required|integer|min:10|max:60',
            'pregnancy_number'       => 'required|integer|min:1|max:20',
            'live_children_count'    => 'required|integer|min:0|max:20',
            'miscarriage_history'    => 'required|integer|min:0|max:10',
            'mother_disease_history' => 'nullable|string|max:255',
        ]);

        // 8️⃣ Update data pregnant_mothers milik user login
        $mother = PregnantMother::where('user_id', $user->id)->first();

        if (!$mother) {
            return response()->json(['message' => 'Data ibu tidak ditemukan.'], 404);
        }

        $mother->update([
            'mother_age'             => $request->mother_age,
            'pregnancy_number'       => $request->pregnancy_number,
            'live_children_count'    => $request->live_children_count,
            'miscarriage_history'    => $request->miscarriage_history,
            'mother_disease_history' => $request->mother_disease_history,
        ]);

        return response()->json([
            'message' => 'Data ibu berhasil dilengkapi.',
            'data'    => $mother
        ], 200);
    }

    public function completeProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'phone'      => 'nullable|string|max:20',
            'address'    => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'photo'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'phone.string'    => 'Nomor telepon harus berupa teks.',
            'phone.max'       => 'Nomor telepon maksimal 20 karakter.',
            'address.string'  => 'Alamat harus berupa teks.',
            'address.max'     => 'Alamat maksimal 255 karakter.',
            'birth_date.date' => 'Tanggal lahir tidak valid.',
            'photo.image'     => 'File foto harus berupa gambar.',
            'photo.mimes'     => 'Format foto harus JPEG, PNG, atau JPG.',
            'photo.max'       => 'Ukuran foto maksimal 2 MB.',
        ]);

        if ($request->hasFile('photo')) {
            if ($user->photo && file_exists(public_path('assets/images/' . $user->photo))) {
                unlink(public_path('assets/images/' . $user->photo));
            }

            $filename = Str::random(20) . '.' . $request->file('photo')->getClientOriginalExtension();
            $request->file('photo')->move(public_path('assets/images'), $filename);
            $user->photo = $filename;
        }

        $user->phone      = $request->phone ?? $user->phone;
        $user->address    = $request->address ?? $user->address;
        $user->birth_date = $request->birth_date ?? $user->birth_date;
        $user->save();

        // ✅ Pastikan birth_date diubah jadi Carbon sebelum format
        $formatDate = fn($date) => $date ? Carbon::parse($date)->format('Y-m-d') : null;

        return response()->json([
            'message' => 'Profil berhasil dilengkapi.',
            'user'    => [
                'id'         => $user->id,
                'name'       => $user->name,
                'email'      => $user->email,
                'role'       => $user->role,
                'village_id' => $user->village_id,
                'phone'      => $user->phone,
                'address'    => $user->address,
                'birth_date' => $formatDate($user->birth_date),
                'photo'      => $user->photo ? asset('assets/images/' . $user->photo) : null,
                'created_at' => $formatDate($user->created_at),
                'updated_at' => $formatDate($user->updated_at),
            ]
        ], 200);
    }

    public function getRegionList()
    {
        // Ambil semua desa/kelurahan beserta relasi kecamatan, kota, dan provinsi
        $villages = VillageModel::with('district.city.province')
            ->orderBy('name', 'asc') // urutkan berdasarkan nama desa/kelurahan
            ->get();

        // Format data untuk API
        $regions = $villages->map(function ($village) {
            return [
                'id'   => $village->id,
                'name' => trim("{$village->name}, {$village->district->name}, {$village->district->city->name}, {$village->district->city->province->name}", ', '),
            ];
        });

        // Response JSON
        return response()->json([
            'message' => 'Daftar desa/kelurahan berhasil diambil.',
            'data'    => $regions,
        ]);
    }
}
