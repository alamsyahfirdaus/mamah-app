<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Login routes
Route::middleware('guest')->group(function () {
    Route::get('/', [LoginController::class, 'index'])->name('login');
    Route::get('login', [LoginController::class, 'index'])->name('login');
    Route::post('login', [LoginController::class, 'authenticate']);
});

Route::get('logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');
