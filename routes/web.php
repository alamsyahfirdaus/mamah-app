<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'index'])->name('role');
    Route::match(['get', 'post'], '/login', [AuthController::class, 'login'])->name('login');
});

Route::middleware('auth')->group(function () {
    Route::middleware('auth')->group(function () {
        // Logout dan Dashboard
        Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

        // Kelola User (CRUD)
        Route::prefix('users')->name('user.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
            Route::match(['post', 'put'], '/store', [UserController::class, 'store'])->name('store');
            Route::get('/{id}/show', [UserController::class, 'show'])->name('show');
            Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
        });
    });
});
