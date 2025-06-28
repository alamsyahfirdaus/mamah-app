<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ScreeningController;
use App\Http\Controllers\Api\EducationController;
use App\Http\Controllers\Api\SupportGroupController;
use App\Http\Controllers\Api\ConsultationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

// =====================
// PUBLIC ROUTES
// =====================

// Registrasi dan login (tidak memerlukan token)
Route::post('/register', [AuthController::class, 'register']); // Daftar akun
Route::post('/login', [AuthController::class, 'login']);       // Login dan dapatkan token

// =====================
// PROTECTED ROUTES (dengan middleware sanctum)
// =====================
Route::middleware('auth:sanctum')->group(function () {

    // mengisi data userr
    Route::post('/complete-profile', [AuthController::class, 'completeProfile']);
    //
    // ---------------------
    // AUTH
    // ---------------------
    Route::post('/logout', [AuthController::class, 'logout']); // Logout dan hapus token

    //mengirim data nama dan foto di bernda
    Route::middleware('auth:sanctum')->get('/me', function (Request $request) {
        $user = $request->user();

        $user->photo = $user->photo
            ? URL::to('/') . '/storage/' . $user->photo
            : null;

        return response()->json($user);
    });

    // ---------------------
    // PROFILE
    // ---------------------
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'show']);   // Tampilkan profil user login
        Route::put('/update', [ProfileController::class, 'update']); // Update profil user
    });

    // ---------------------
    // SKRINING
    // ---------------------
    Route::prefix('screening')->group(function () {
        Route::get('/questions', [ScreeningController::class, 'questions']); // Ambil soal EPDS
        Route::post('/submit', [ScreeningController::class, 'submit']);      // Kirim hasil skrining
        Route::get('/result', [ScreeningController::class, 'screeningResult']); // Hasil skrining user login
        Route::get('/user/{id}', [ScreeningController::class, 'showUserResult']); // Hasil user by ID
        Route::get('/all/user', [ScreeningController::class, 'listUserWithScreening']); // Semua ibu + hasil
    });

    // ---------------------
    // EDUKASI
    // ---------------------
    Route::prefix('education')->group(function () {
        Route::get('/', [EducationController::class, 'index']);            // Daftar semua materi edukasi
        Route::get('/{id}/show', [EducationController::class, 'show']);    // Detail materi edukasi
        Route::get('/latest', [EducationController::class, 'latest']);     // Materi edukasi terbaru
    });

    // ---------------------
    // SUPPORT GROUP / DISKUSI
    // ---------------------
    Route::prefix('groups')->group(function () {
        Route::get('/', [SupportGroupController::class, 'index']);             // Daftar grup
        Route::match(['post', 'put'], '/store', [SupportGroupController::class, 'store']); // Buat atau update grup
        Route::get('/{id}/show', [SupportGroupController::class, 'show']);     // Detail grup
        Route::delete('/{id}/delete', [SupportGroupController::class, 'destroyGroup']); // Hapus grup
        Route::post('/{id}/store', [SupportGroupController::class, 'sendMessage']);     // Kirim atau edit pesan
        Route::get('/{id}/messages', [SupportGroupController::class, 'messages']);      // List pesan grup
        Route::delete('/{groupId}/messages/{messageId}', [SupportGroupController::class, 'deleteMessage']); // Hapus pesan
    });

    // ---------------------
    // KONSULTASI
    // ---------------------
    Route::prefix('consultations')->group(function () {
        Route::get('/', [ConsultationController::class, 'index']);            // Daftar konsultasi
        Route::match(['post', 'put'], '/store', [ConsultationController::class, 'store']); // Kirim atau update konsultasi
        Route::get('/{id}/show', [ConsultationController::class, 'show']);         // Detail konsultasi + balasan
        Route::delete('/{id}/delete', [ConsultationController::class, 'destroy']); // Hapus konsultasi
        Route::match(['post', 'put'], '/reply', [ConsultationController::class, 'reply']); // Balasan konsultasi
        Route::delete('/reply/{replyId}/delete', [ConsultationController::class, 'deleteReply']); // Hapus balasan konsultasi
        Route::get('/bidan', [ConsultationController::class, 'getDaftarBidan']);  // Daftar bidan
    });
});
