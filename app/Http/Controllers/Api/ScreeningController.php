<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ScreeningQuestion;
use App\Models\ScreeningResult;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScreeningController extends Controller
{
    // Mengambil seluruh pertanyaan EPDS beserta pilihan jawabannya
    public function questions()
    {
        $questions = ScreeningQuestion::with('choices') // Ambil relasi pilihan jawaban
            ->orderBy('question_no', 'asc')             // Urutkan berdasarkan nomor pertanyaan
            ->get();

        // Jika tidak ada pertanyaan ditemukan
        if ($questions->isEmpty()) {
            return response()->json([
                'message' => 'Tidak ada pertanyaan yang tersedia saat ini.',
                'data' => []
            ], 404);
        }

        // Return daftar pertanyaan yang ditemukan
        return response()->json([
            'message' => 'Daftar pertanyaan berhasil diambil.',
            'data' => $questions
        ]);
    }

    // Menyimpan atau memperbarui hasil skrining EPDS
    public function submit(Request $request)
    {
        // Validasi input jawaban dari pengguna
        $request->validate([
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|exists:screening_questions,id',
            'answers.*.choice_score' => 'required|integer|min:0|max:3',
        ], [
            'answers.required' => 'Jawaban tidak boleh kosong.',
            'answers.*.question_id.required' => 'ID pertanyaan wajib diisi.',
            'answers.*.choice_score.required' => 'Skor pilihan wajib diisi.',
        ]);

        $user = Auth::user(); // Ambil user yang login
        $totalScore = collect($request->answers)->sum('choice_score'); // Hitung total skor

        // Cek apakah sudah ada hasil skrining sebelumnya
        $existing = ScreeningResult::where('user_id', $user->id)->latest()->first();

        if ($existing) {
            // Jika sudah ada, lakukan update
            $existing->update([
                'score'          => $totalScore,
                'recommendation' => $this->getRecommendation($totalScore),
                'category'       => $this->getCategory($totalScore),
            ]);

            return response()->json([
                'message' => 'Hasil skrining berhasil diperbarui.',
                'result'  => $existing
            ]);
        } else {
            // Jika belum ada, buat baru
            $result = ScreeningResult::create([
                'user_id'        => $user->id,
                'score'          => $totalScore,
                'recommendation' => $this->getRecommendation($totalScore),
                'category'       => $this->getCategory($totalScore),
            ]);

            return response()->json([
                'message' => 'Hasil skrining berhasil disimpan.',
                'result'  => $result
            ], 201);
        }
    }

    // Menampilkan hasil skrining milik user yang sedang login
    public function screeningResult()
    {
        // Ambil hasil skrining milik user (karena hanya 1 data per user)
        $result = ScreeningResult::where('user_id', Auth::id())->first();

        // Jika belum pernah melakukan skrining
        if (!$result) {
            return response()->json([
                'message' => 'Belum ada hasil skrining.'
            ], 404);
        }

        // Kembalikan data hasil skrining
        return response()->json([
            'message' => 'Hasil skrining berhasil diambil.',
            'data'    => $result
        ]);
    }

    // Menentukan rekomendasi berdasarkan skor total EPDS
    private function getRecommendation($score)
    {
        if ($score <= 9) {
            return 'Risiko rendah. Tidak perlu tindakan khusus, cukup pantau secara berkala.';
        } elseif ($score <= 12) {
            return 'Risiko sedang. Disarankan konsultasi lanjutan dengan tenaga kesehatan.';
        } else {
            return 'Risiko tinggi. Segera lakukan konsultasi atau rujukan ke psikolog.';
        }
    }

    // Menentukan kategori risiko berdasarkan skor
    private function getCategory($score)
    {
        if ($score >= 13) return 'tinggi';
        if ($score >= 10) return 'sedang';
        return 'rendah';
    }

    // Menampilkan hasil skrining milik pengguna tertentu (khusus untuk bidan)
    public function showUserResult($id)
    {
        // Ambil data user yang sedang login
        $authUser = Auth::user();

        // Validasi: hanya role 'bidan' yang diizinkan mengakses
        if ($authUser->role !== 'bidan') {
            return response()->json([
                'message' => 'Akses ditolak. Hanya bidan yang dapat melihat hasil skrining pengguna lain.'
            ], 403);
        }

        // Ambil hasil skrining berdasarkan user_id (karena 1 user hanya punya 1 data)
        $result = ScreeningResult::where('user_id', $id)->first();

        // Jika tidak ditemukan hasil skrining
        if (!$result) {
            return response()->json([
                'message' => 'Hasil skrining tidak ditemukan untuk pengguna ini.'
            ], 404);
        }

        // Kembalikan data hasil skrining
        return response()->json([
            'message' => 'Hasil skrining pengguna berhasil diambil.',
            'data'    => $result
        ]);
    }

    // Menampilkan daftar ibu beserta hasil skriningnya (khusus untuk bidan)
    public function listUserWithScreening()
    {
        // Cek apakah user yang sedang login adalah bidan
        $authUser = Auth::user();

        if ($authUser->role !== 'bidan') {
            return response()->json([
                'message' => 'Akses ditolak. Hanya bidan yang dapat melihat data ibu.'
            ], 403);
        }

        // Ambil semua user dengan role "ibu" dan relasi hasil skriningnya
        $mothers = User::where('role', 'ibu')
            ->with('screeningResult') // Pastikan relasi ini sudah didefinisikan di model User
            ->get();

        if ($mothers->isEmpty()) {
            return response()->json([
                'message' => 'Belum ada data ibu yang tersedia.'
            ], 404);
        }

        return response()->json([
            'message' => 'Daftar ibu dan hasil skrining berhasil diambil.',
            'data'    => $mothers
        ]);
    }
}
