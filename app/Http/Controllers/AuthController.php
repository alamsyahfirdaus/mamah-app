<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        return view('role-index');
    }

    public function login(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('login-index');
        }

        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        // Coba login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Ambil user yang baru login
            $user = Auth::user();

            // Cek role
            if ($user->role !== 'kia') {
                Auth::logout(); // logout langsung
                return back()->withInput()->with('message', 'Akun Anda tidak memiliki akses.');
            }

            return redirect()->intended('/dashboard');
        }

        // Jika gagal login
        return back()->withInput()->with('message', 'Email atau password salah!');
    }


    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
