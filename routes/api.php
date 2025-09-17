<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ScreeningController;
use App\Http\Controllers\Api\EducationController;
use App\Http\Controllers\Api\SupportGroupController;
use App\Http\Controllers\Api\ConsultationController;

// =====================
// PUBLIC ROUTES
// =====================

// Registrasi dan login (tidak memerlukan token)
Route::post('/register', [AuthController::class, 'register']); // Daftar akun
Route::post('/login', [AuthController::class, 'login']);       // Login dan dapatkan token
Route::get('/regions', [AuthController::class, 'getRegionList']); // Ambil daftar kecamatan, kota, provinsi

// =====================
// PROTECTED ROUTES (memerlukan token Sanctum)
// =====================
Route::middleware('auth:sanctum')->group(function () {

    // ---------------------
    // AUTH
    // ---------------------
    Route::post('/logout', [AuthController::class, 'logout']); // Logout dan hapus token
    Route::post('/complete-profile', [AuthController::class, 'completeProfile']); // Lengkapi profil user

    // Mendapatkan data user login (nama & foto)
    Route::get('/me', function (Request $request) {
        $user = $request->user();
        $user->photo = $user->photo ? URL::to('/') . '/storage/' . $user->photo : null;

        return response()->json($user);
    });

    // ---------------------
    // PROFILE
    // ---------------------
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'show']);        // Tampilkan profil user login
        Route::put('/update', [ProfileController::class, 'update']); // Update profil user
    });

    // ---------------------
    // SKRINING
    // ---------------------
    Route::prefix('screening')->group(function () {
        Route::get('/questions', [ScreeningController::class, 'questions']);          // Ambil soal EPDS
        Route::post('/submit', [ScreeningController::class, 'submit']);               // Kirim hasil skrining
        Route::get('/result', [ScreeningController::class, 'screeningResult']);      // Hasil skrining user login
        Route::get('/user/{id}', [ScreeningController::class, 'showUserResult']);    // Hasil skrining user by ID
        Route::get('/all/user', [ScreeningController::class, 'listUserWithScreening']); // Semua ibu + hasil
    });

    // ---------------------
    // EDUKASI
    // ---------------------
    Route::prefix('education')->group(function () {
        // List semua materi edukasi (GET / POST) dengan filter category_id opsional
        Route::match(['get', 'post'], '/', [EducationController::class, 'listEducationalModules']);
        // Detail materi edukasi berdasarkan ID
        Route::get('/{id}/show', [EducationController::class, 'show']);
        // Materi edukasi terbaru
        Route::get('/latest', [EducationController::class, 'latest']);
        // List kategori materi edukasi
        Route::get('/categories', [EducationController::class, 'listCategories']);
    });

    // ---------------------
    // RELAKSASI
    // ---------------------
    Route::match(['get', 'post'], '/relaxation', [EducationController::class, 'listRelaxationVideos']);

    // ---------------------
    // SUPPORT GROUP / DISKUSI
    // ---------------------
    Route::prefix('groups')->group(function () {
        Route::get('/', [SupportGroupController::class, 'index']);                     // Daftar grup
        Route::match(['post', 'put'], '/store', [SupportGroupController::class, 'store']); // Buat/update grup
        Route::get('/{id}/show', [SupportGroupController::class, 'show']);            // Detail grup
        Route::delete('/{id}/delete', [SupportGroupController::class, 'destroyGroup']); // Hapus grup
        Route::post('/{id}/store', [SupportGroupController::class, 'sendMessage']);   // Kirim/edit pesan
        Route::get('/{id}/messages', [SupportGroupController::class, 'messages']);    // List pesan grup
        Route::delete('/{groupId}/messages/{messageId}', [SupportGroupController::class, 'deleteMessage']); // Hapus pesan
    });

    // ---------------------
    // KONSULTASI
    // ---------------------
    Route::prefix('consultations')->group(function () {
        Route::get('/', [ConsultationController::class, 'index']);                    // Daftar konsultasi
        Route::match(['post', 'put'], '/store', [ConsultationController::class, 'store']); // Kirim/update konsultasi
        Route::get('/{id}/show', [ConsultationController::class, 'show']);           // Detail konsultasi + balasan
        Route::match(['post', 'put'], '/reply', [ConsultationController::class, 'reply']); // Balasan konsultasi
        Route::delete('/reply/{replyId}/delete', [ConsultationController::class, 'deleteReply']); // Hapus balasan
        Route::delete('/{id}/delete', [ConsultationController::class, 'destroy']);   // Hapus konsultasi
        Route::get('/pasangan', [ConsultationController::class, 'getDaftarPasangan']); // Daftar bidan
    });
});
