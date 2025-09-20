<?php

namespace App\Http\Controllers;

use App\Models\PregnantMother;
use App\Models\User;
use App\Models\VillageModel;
use Carbon\Carbon;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with([
            'village.district.city.province'
        ])
            ->where('id', '!=', 1)
            ->orderByDesc('id')
            ->get();
        return view('user-index', [
            'title' => 'Pengguna',
            'users' => $users
        ]);
    }

    public function create()
    {
        $data = [
            'title'     => 'Pengguna',
            'villages'   => VillageModel::getRegionList(),
        ];

        return view('user-store', $data);
    }

    public function edit($id)
    {
        try {
            $userId = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'ID tidak valid.');
        }

        $user = User::find($userId);

        if (!$user) {
            return redirect()->back()->with('error', 'Pengguna tidak ditemukan.');
        }

        $data = [
            'title'     => 'Pengguna',
            'villages'  => VillageModel::getRegionList(),
            'user'      => $user,
        ];

        return view('user-store', $data);
    }

    public function store(Request $request)
    {
        $userId = null;

        if (!empty($request->id)) {
            try {
                $userId = Crypt::decrypt($request->id);
            } catch (DecryptException $e) {
                return redirect()->back()
                    ->with('error', 'ID tidak valid.');
            }
        }

        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'phone'       => 'nullable|string|max:20',
            'birth_date'  => 'nullable|date_format:d/m/Y',
            'address'     => 'nullable|string',
            'role'        => 'required|in:kia,ibu,bidan',
            'village_id'  => 'nullable|exists:villages,id|required_if:role,ibu,bidan',
        ], [
            'name.required'        => 'Nama wajib diisi.',
            'name.max'             => 'Nama tidak boleh lebih dari :max karakter.',
            'email.required'       => 'Email wajib diisi.',
            'email.email'          => 'Format email tidak valid.',
            'email.max'            => 'Email tidak boleh lebih dari :max karakter.',
            'email.unique'         => 'Email sudah digunakan oleh pengguna lain.',
            'phone.max'            => 'Nomor HP tidak boleh lebih dari :max karakter.',
            'birth_date.date'      => 'Format tanggal lahir tidak valid.',
            'address.string'       => 'Alamat harus berupa teks.',
            'village_id.required_if' => 'Kelurahan/Desa wajib dipilih jika peran adalah ibu atau bidan.',
            'village_id.exists'    => 'Kelurahan/Desa yang dipilih tidak valid.',
            'role.required'        => 'Peran wajib dipilih.',
            'role.in'              => 'Peran yang dipilih tidak valid.',
        ]);

        $user = $userId ? User::findOrFail($userId) : new User();

        $user->name       = $request->name;
        $user->email      = $request->email;
        $user->phone      = $request->phone;
        $user->birth_date = $request->birth_date
            ? Carbon::createFromFormat('d/m/Y', $request->birth_date)->format('Y-m-d')
            : null;
        $user->address    = $request->address;
        $user->village_id = $request->village_id; // pakai kelurahan
        $user->role       = $request->role;
        $user->is_verified = 1;

        if (!$userId) {
            $user->password = Hash::make($request->email); // password default = email yang di-hash
        }

        $user->save();

        $message = $userId
            ? 'Data pengguna berhasil diperbarui.'
            : 'Data pengguna baru berhasil ditambahkan.';

        return $userId && $user->role != 'kia'
            ? redirect()->route('user.show', Crypt::encrypt($userId))->with('success', $message)
            : redirect()->route('user.index')->with('success', $message);
    }

    public function show(string $id)
    {
        try {
            $userId = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'ID tidak valid.');
        }

        $user = User::with('village.district.city.province')->find($userId);

        if (!$user) {
            return redirect()->back()->with('error', 'Pengguna tidak ditemukan.');
        }

        $mother = PregnantMother::with('user')
            ->where('user_id', $userId)
            ->first();

        $users = User::where('role', '!=', 'kia')
            ->orderBy('name', 'asc')
            ->get()
            ->mapWithKeys(fn($item) => [
                $item->id => "{$item->name} ({$item->email})"
            ]);

        $data = [
            'title'  => 'Pengguna',
            'users'  => $users,
            'user'   => $user,
            'mother' => $mother,
        ];

        return view('user-show', $data);
    }

    public function destroy(string $id)
    {
        try {
            $userId = Crypt::decrypt($id);

            $user = User::findOrFail($userId);

            $user->delete();

            return redirect()->back()->with('success', 'Data pengguna berhasil dihapus.');
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'ID tidak valid.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data pengguna.');
        }
    }
}
