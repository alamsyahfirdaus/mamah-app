<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('id', 'desc')->get();

        $data = [
            'title' => 'Pengguna',
            'users' => $users
        ];

        return view('user-index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Pengguna',
        ];

        return view('user-store', $data);
    }

    public function edit($id)
    {
        try {
            $userId = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->route('user.create');
        }

        $user = User::findOrFail($userId);

        $data = [
            'title' => 'Pengguna',
            'user'  => $user,
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
                    ->with('error', 'ID tidak valid.')
                    ->withInput();
            }
        }

        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'phone'      => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'address'    => 'nullable|string',
            'role'       => 'required|in:kia,ibu,bidan',
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
            'role.required'        => 'Peran wajib dipilih.',
            'role.in'              => 'Peran yang dipilih tidak valid.',
        ]);

        $user = $userId ? User::findOrFail($userId) : new User();

        $user->name        = $request->name;
        $user->email       = $request->email;
        $user->phone       = $request->phone;
        $user->birth_date  = $request->birth_date;
        $user->address     = $request->address;
        $user->role        = $request->role;
        $user->is_verified = 1;

        if (!$userId) {
            $user->password = Hash::make($request->email); // password = email yang di-hash
        }

        $user->save();

        $message = $userId
            ? 'Data pengguna berhasil diperbarui.'
            : 'Data pengguna baru berhasil ditambahkan.';

        return redirect()->route('user.index')->with('success', $message);
    }

    public function show(string $id)
    {
        //
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
