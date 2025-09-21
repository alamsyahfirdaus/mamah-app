<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PregnantMother;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show()
    {
        // Ambil user login + relasi lokasi
        $user = Auth::user()->load('village.district.city.province');

        // URL foto
        $photoUrl = $user->photo
            ? URL::to('/') . '/assets/images/' . $user->photo
            : null;

        // Ambil nama per level lokasi
        $village  = optional($user->village)->name;
        $district = optional($user->village->district)->name;
        $city     = optional(optional($user->village->district)->city)->name;
        $province = optional(optional(optional($user->village->district)->city)->province)->name;

        // Gabungan alamat
        $fullAddress = implode(', ', array_filter([$village, $district, $city, $province]));

        // Data dasar user
        $userData = [
            'id'             => $user->id,
            'name'           => $user->name,
            'email'          => $user->email,
            'role'           => $user->role,
            'birth_date'     => $user->birth_date,
            'phone'          => $user->phone,
            'address'        => $user->address,
            'village_id'     => $user->village_id,
            'occupation'     => $user->occupation,
            'photo'          => $photoUrl,
            'is_verified'    => $user->is_verified,
            'remember_token' => $user->remember_token,
            'created_at'     => $user->created_at,
            'updated_at'     => $user->updated_at,

            // Tambahan custom lokasi
            'village_name'   => $village,
            'district_name'  => $district,
            'city_name'      => $city,
            'province_name'  => $province,
            'full_address'   => $fullAddress,
        ];

        // Jika role = ibu, tambahkan data dari pregnant_mothers
        if ($user->role === 'ibu') {
            $pregnantMother = PregnantMother::where('user_id', $user->id)->first();
            $userData['mother_age']             = $pregnantMother->mother_age ?? null;
            $userData['pregnancy_number']       = $pregnantMother->pregnancy_number ?? null;
            $userData['live_children_count']    = $pregnantMother->live_children_count ?? null;
            $userData['miscarriage_history']    = $pregnantMother->miscarriage_history ?? null;
            $userData['mother_disease_history'] = $pregnantMother->mother_disease_history ?? null;
        }

        return response()->json([
            'message' => 'Data profil berhasil diambil.',
            'user'    => $userData,
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user(); // User login

        // Validasi input
        $validated = $request->validate([
            'name'       => 'nullable|string|max:255',
            'email'      => ['nullable', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone'      => 'nullable|string|max:20',
            'address'    => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'password'   => 'nullable|string|min:6',
            'photo'      => 'nullable|file|image|mimes:jpeg,png,jpg|max:2048',

            // Validasi khusus untuk pregnant_mothers
            'mother_age'             => 'nullable|integer|min:10|max:70',
            'pregnancy_number'       => 'nullable|integer|min:1',
            'live_children_count'    => 'nullable|integer|min:0',
            'miscarriage_history'    => 'nullable|integer|min:0',
            'mother_disease_history' => 'nullable|string',
        ]);

        // Path default foto lama
        $photoFileName = $user->photo;

        // Upload foto baru ke assets/images
        if ($request->hasFile('photo')) {
            $extension    = $request->file('photo')->getClientOriginalExtension();
            $newFileName  = Str::random(20) . '.' . $extension;

            // Simpan file ke public/assets/images
            $request->file('photo')->move(public_path('assets/images'), $newFileName);

            // Simpan path relatif
            $photoFileName = 'assets/images/' . $newFileName;
        }

        // Data untuk update users
        $dataToUpdate = [
            'name'       => $request->name ?? $user->name,
            'email'      => $request->email ?? $user->email,
            'phone'      => $request->phone ?? $user->phone,
            'address'    => $request->address ?? $user->address,
            'birth_date' => $request->birth_date ?? $user->birth_date,
            'photo'      => $photoFileName,
        ];

        if ($request->filled('password')) {
            $dataToUpdate['password'] = Hash::make($request->password);
        }

        try {
            // Update tabel users
            $user->update($dataToUpdate);

            // Jika role ibu → update juga tabel pregnant_mothers
            if ($user->role === 'ibu') {
                PregnantMother::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'mother_age'             => $request->mother_age ?? null,
                        'pregnancy_number'       => $request->pregnancy_number ?? null,
                        'live_children_count'    => $request->live_children_count ?? null,
                        'miscarriage_history'    => $request->miscarriage_history ?? 0,
                        'mother_disease_history' => $request->mother_disease_history ?? null,
                    ]
                );
            }
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal memperbarui profil.',
                'error'   => $e->getMessage(),
            ], 500);
        }

        // Generate full URL foto
        $user->photo = $user->photo ? url($user->photo) : null;

        return response()->json([
            'status'  => 'success',
            'message' => 'Profil berhasil diperbarui.',
            'data'    => [
                'profile' => $user,
            ]
        ]);
    }
}
