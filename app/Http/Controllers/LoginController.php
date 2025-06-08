<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    // Tampilkan halaman login
    public function index()
    {
        return view('login');
    }
}
