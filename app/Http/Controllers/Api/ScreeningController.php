<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ScreeningLevel;
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

    private function evaluateScreening(array $responses)
    {
        $answers = [];        // Array untuk menyimpan jawaban dalam format [question_no => score]
        $totalScore = 0;      // Total skor untuk penilaian umum

        // Loop setiap jawaban untuk membangun $answers dan menghitung total skor
        foreach ($responses as $response) {
            $questionNo = $response['question_no'];
            $score = $response['score'];
            $answers[$questionNo] = $score;  // Simpan skor berdasarkan nomor pertanyaan
            $totalScore += $score;          // Akumulasi skor
        }

        // Ambil kategori skrining berdasarkan total skor (tanpa keterkaitan ke pertanyaan tertentu)
        $level = ScreeningLevel::whereNull('question_id')
            ->where('min_score', '<=', $totalScore)
            ->where(function ($q) use ($totalScore) {
                $q->where('max_score', '>=', $totalScore)
                    ->orWhereNull('max_score'); // Jika max_score tidak ditentukan (tak terbatas)
            })
            ->first();

        // Ambil semua level khusus yang terkait dengan pertanyaan spesifik
        $specialLevels = ScreeningLevel::whereNotNull('question_id')->get();

        $specialRecommendations = []; // Array untuk menyimpan rekomendasi khusus jika ada yang cocok

        // Evaluasi setiap kondisi khusus
        foreach ($specialLevels as $specialLevel) {
            $question = $specialLevel->question; // Ambil pertanyaan terkait (pastikan relasi question() ada)
            if (!$question) continue;            // Lewati jika relasi tidak tersedia

            $questionNo = $question->question_no;

            // Cek apakah user menjawab pertanyaan ini
            if (!isset($answers[$questionNo])) continue;

            $score = $answers[$questionNo];

            // Cek apakah skor masuk dalam rentang min–max dari level khusus
            if (
                $specialLevel->min_score <= $score &&
                (is_null($specialLevel->max_score) || $score <= $specialLevel->max_score)
            ) {
                $specialRecommendations[] = $specialLevel->recommendation; // Simpan rekomendasi
            }
        }

        // Gabungkan rekomendasi umum dengan rekomendasi khusus jika ada
        $recommendation = $level?->recommendation ?? 'Tidak tersedia rekomendasi.';
        if (!empty($specialRecommendations)) {
            $recommendation .= "\n\nCatatan Khusus:\n- " . implode("\n- ", $specialRecommendations);
        }

        // Kembalikan hasil skrining lengkap
        return [
            'total_score' => $totalScore,
            'category' => $level?->category ?? 'Tidak diketahui',
            'recommendation' => $recommendation,
            'answers' => collect($answers)->map(function ($score, $questionNo) {
                return [
                    'question_no' => $questionNo,
                    'score' => $score
                ];
            })->values()->all()
        ];
    }

    public function submit(Request $request)
    {
        // Validasi input dari user (array of answers dengan question_id & score)
        $request->validate([
            'answers' => 'required|array',
            'answers.*.question_id'   => 'required|exists:screening_questions,id',
            'answers.*.choice_score'  => 'required|integer|min:0|max:3',
        ], [
            'answers.required' => 'Jawaban tidak boleh kosong.',
            'answers.*.question_id.required' => 'ID pertanyaan wajib diisi.',
            'answers.*.choice_score.required' => 'Skor pilihan wajib diisi.',
        ]);

        $user = Auth::user(); // Ambil data user yang login

        // Konversi input menjadi format {question_no, score}
        $responses = collect($request->answers)->map(function ($item) {
            $question = ScreeningQuestion::find($item['question_id']); // Ambil pertanyaan
            return [
                'question_no' => $question->question_no,               // Ambil nomor urut soal
                'score'       => $item['choice_score']                 // Ambil skor jawaban
            ];
        })->toArray();

        // Evaluasi hasil skrining dengan fungsi terpisah
        $resultData = $this->evaluateScreening($responses);

        // Cek apakah user sudah memiliki hasil skrining sebelumnya
        $existing = ScreeningResult::where('user_id', $user->id)->latest()->first();

        if ($existing) {
            // Update data hasil lama jika sudah ada
            $existing->update([
                'score'          => $resultData['total_score'],
                'category'       => $resultData['category'],
                'recommendation' => $resultData['recommendation'],
            ]);

            return response()->json([
                'message' => 'Hasil skrining berhasil diperbarui.',
                'result'  => $existing
            ]);
        }

        // Jika belum ada hasil, simpan baru
        $result = ScreeningResult::create([
            'user_id'        => $user->id,
            'score'          => $resultData['total_score'],
            'category'       => $resultData['category'],
            'recommendation' => $resultData['recommendation'],
        ]);

        return response()->json([
            'message' => 'Hasil skrining berhasil disimpan.',
            'result'  => $result
        ], 201);
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
