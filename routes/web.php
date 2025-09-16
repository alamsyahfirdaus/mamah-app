<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\DiscussionController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InterpretationController;
use App\Http\Controllers\ScreeningController;
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
            Route::delete('/{id}/delete', [UserController::class, 'destroy'])->name('delete');
        });

        // Skrining
        Route::prefix('question')->name('question.')->middleware('auth')->group(function () {
            Route::get('/', [ScreeningController::class, 'index'])->name('index');
            Route::get('/create', [ScreeningController::class, 'create'])->name('create');
            Route::get('/{id}/edit', [ScreeningController::class, 'edit'])->name('edit');
            Route::match(['post', 'put'], '/store', [ScreeningController::class, 'store'])->name('store');
            Route::delete('/{id}/delete', [ScreeningController::class, 'destroy'])->name('delete');
            Route::get('/reorder/{id}/{direction}', [ScreeningController::class, 'reorder'])->name('reorder');
            Route::get('/result', [ScreeningController::class, 'screeningResult'])->name('result');
            Route::get('/{id}/special', [ScreeningController::class, 'updateSpecial'])->name('special');
        });

        // Interpretasi
        Route::prefix('interpretation')->name('interpretation.')->middleware('auth')->group(function () {
            Route::get('/', [InterpretationController::class, 'index'])->name('index');
            Route::get('/create', [InterpretationController::class, 'create'])->name('create');
            Route::get('/{id}/edit', [InterpretationController::class, 'edit'])->name('edit');
            Route::match(['post', 'put'], '/store', [InterpretationController::class, 'store'])->name('store');
            Route::delete('/{id}/delete', [InterpretationController::class, 'destroy'])->name('delete');
            Route::get('/reorder/{id}/{direction}', [InterpretationController::class, 'reorder'])->name('reorder');
            Route::get('/result', [InterpretationController::class, 'screeningResult'])->name('result');
            Route::get('/{id}/special', [InterpretationController::class, 'updateSpecial'])->name('special');
        });

        // Materi Edukasi
        Route::prefix('education')->name('education.')->middleware('auth')->group(function () {
            Route::get('/', [EducationController::class, 'index'])->name('index');            // List semua materi
            Route::get('/create', [EducationController::class, 'create'])->name('create');    // Form tambah
            Route::get('/{id}/edit', [EducationController::class, 'edit'])->name('edit');     // Form edit
            Route::match(['post', 'put'], '/store', [EducationController::class, 'store'])->name('store'); // Simpan
            Route::delete('/{id}/delete', [EducationController::class, 'destroy'])->name('delete');        // Hapus
            Route::get('/{id}/show', [EducationController::class, 'show'])->name('show');     // (Opsional) Lihat detail materi
        });

        // Grup Diskusi
        Route::prefix('discussion')->name('discussion.')->middleware('auth')->group(function () {
            Route::get('/', [DiscussionController::class, 'index'])->name('index');             // List semua diskusi
            Route::get('/create', [DiscussionController::class, 'create'])->name('create');     // Form tambah diskusi
            Route::get('/{id}/edit', [DiscussionController::class, 'edit'])->name('edit');      // Form edit diskusi
            Route::match(['post', 'put'], '/store', [DiscussionController::class, 'store'])->name('store'); // Simpan
            Route::delete('/{id}/delete', [DiscussionController::class, 'destroy'])->name('delete'); // Hapus
            Route::get('/{id}/show', [DiscussionController::class, 'show'])->name('show');      // Lihat detail diskusi
            Route::post('/{id}/reply', [DiscussionController::class, 'reply'])->name('reply');  // Balas diskusi (opsional)
        });

        // Konsultasi Ibu
        Route::prefix('consultation')->name('consultation.')->middleware('auth')->group(function () {
            Route::get('/', [ConsultationController::class, 'index'])->name('index');           // List konsultasi
            Route::get('/create', [ConsultationController::class, 'create'])->name('create');   // Form permintaan konsultasi
            Route::get('/{id}/show', [ConsultationController::class, 'show'])->name('show');    // Lihat detail konsultasi
            Route::post('/store', [ConsultationController::class, 'store'])->name('store');     // Simpan konsultasi
            Route::post('/{id}/reply', [ConsultationController::class, 'reply'])->name('reply'); // Balas konsultasi (oleh bidan/admin)
            Route::delete('/{id}/delete', [ConsultationController::class, 'destroy'])->name('delete'); // Hapus
        });
    });
});
