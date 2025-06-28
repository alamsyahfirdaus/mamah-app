<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\ConsultationReply;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class ConsultationController extends Controller
{
    public function index()
    {
        // Ambil user yang sedang login
        $user = Auth::user();

        // Jika role bidan, ambil semua konsultasi; jika bukan (ibu), hanya konsultasi miliknya
        $consultations = $user->role === 'bidan'
            ? Consultation::with(['ibu:id,name', 'bidan:id,name'])->orderBy('created_at', 'desc')->get()
            : Consultation::with(['ibu:id,name', 'bidan:id,name'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $data = $consultations->map(function ($item) {
            return [
                'id'         => $item->id,
                'topic'      => $item->topic,
                'user_id'    => $item->ibu->id,
                'name'       => $item->ibu->name,
                'bidan_id'   => $item->bidan->id,
                'bidan'      => $item->bidan->name,
                'created_at' => $item->created_at->format('Y-m-d H:i'),
            ];
        });

        return response()->json([
            'message' => 'Daftar konsultasi berhasil diambil.',
            'data'    => $data
        ]);
    }

    public function getDaftarBidan()
    {
        // Pastikan hanya ibu yang bisa mengakses
        if (Auth::user()->role !== 'ibu') {
            return response()->json([
                'message' => 'Akses ditolak. Hanya pengguna dengan peran ibu yang dapat melihat daftar bidan.'
            ], 403);
        }

        // Ambil semua user dengan role 'bidan'
        $bidans = User::where('role', 'bidan')
            ->select('id', 'name', 'email', 'photo') // hanya ambil field yang diperlukan
            ->orderBy('name')
            ->get();

        if ($bidans->isEmpty()) {
            return response()->json([
                'message' => 'Belum ada bidan yang tersedia.',
                'data' => []
            ], 404);
        }

        // Format foto ke URL lengkap
        $bidans->transform(function ($bidan) {
            $bidan->photo = $bidan->photo
                ? URL::to('/') . '/storage/images/' . $bidan->photo
                : null;
            return $bidan;
        });

        return response()->json([
            'message' => 'Daftar bidan berhasil diambil.',
            'data' => $bidans
        ]);
    }

    public function store(Request $request)
    {
        // Ambil ID konsultasi jika dikirim (untuk update)
        $id = $request->input('id');

        // Validasi input termasuk ID jika ada
        $request->validate([
            'id'       => 'nullable|integer|exists:consultations,id',
            'bidan_id' => 'required|exists:users,id',
            'question' => 'required|string|max:1000',
        ], [
            'id.integer'         => 'ID konsultasi tidak valid.',
            'id.exists'          => 'Data konsultasi tidak ditemukan.',
            'bidan_id.required'  => 'Bidan tujuan wajib dipilih.',
            'bidan_id.exists'    => 'Bidan tidak ditemukan.',
            'question.required'  => 'Pertanyaan wajib diisi.',
            'question.string'    => 'Pertanyaan harus berupa teks.',
            'question.max'       => 'Pertanyaan tidak boleh lebih dari 1000 karakter.',
        ]);

        // Jika update, pastikan data milik user yang sedang login
        $consultation = $id
            ? Consultation::where('id', $id)->where('user_id', Auth::id())->first()
            : new Consultation;

        // Jika ID ada tapi tidak ditemukan atau bukan milik user
        if ($id && !$consultation) {
            return response()->json([
                'message' => 'Konsultasi tidak ditemukan atau Anda tidak memiliki izin untuk memperbarui.'
            ], 404);
        }

        // Set atau update data
        $consultation->user_id  = Auth::id();              // Ibu yang mengajukan
        $consultation->bidan_id = $request->bidan_id;      // Bidan yang dituju
        $consultation->topic    = $request->question;      // Pertanyaan atau topik konsultasi

        // Simpan ke database
        $consultation->save();

        return response()->json([
            'message' => $id ? 'Konsultasi berhasil diperbarui.' : 'Konsultasi berhasil dikirim.',
            'data'    => $consultation
        ], $id ? 200 : 201);
    }

    public function show($id)
    {
        // Ambil data konsultasi beserta relasi ibu dan bidan
        $consultation = Consultation::with([
            'ibu:id,name',
            'bidan:id,name',
            'reply'
        ])->find($id);

        // Jika tidak ditemukan
        if (!$consultation) {
            return response()->json([
                'message' => 'Konsultasi tidak ditemukan.'
            ], 404);
        }

        // Kembalikan data dalam format lengkap
        return response()->json([
            'message' => 'Detail konsultasi berhasil diambil.',
            'data' => [
                'id'         => $consultation->id,
                'topic'      => $consultation->topic,
                'user_id'    => $consultation->ibu->id,
                'ibu'        => $consultation->ibu->name,
                'bidan_id'   => $consultation->bidan->id,
                'bidan'      => $consultation->bidan->name,
                'created_at' => $consultation->created_at->format('Y-m-d H:i'),
                'reply'      => $consultation->reply ?? null
            ]
        ]);
    }

    public function destroy($id)
    {
        $user = Auth::user();

        // Cari konsultasi berdasarkan ID
        $consultation = Consultation::find($id);

        // Jika tidak ditemukan
        if (!$consultation) {
            return response()->json([
                'message' => 'Konsultasi tidak ditemukan.'
            ], 404);
        }

        // Periksa apakah user adalah ibu atau bidan yang terkait dengan konsultasi
        $isIbu   = $consultation->user_id === $user->id;
        $isBidan = $consultation->bidan_id === $user->id;

        if (!$isIbu && !$isBidan) {
            return response()->json([
                'message' => 'Anda tidak memiliki izin untuk menghapus konsultasi ini.'
            ], 403);
        }

        // Hapus konsultasi
        $consultation->delete();

        return response()->json([
            'message' => 'Konsultasi berhasil dihapus.'
        ]);
    }

    public function reply(Request $request)
    {
        // Ambil ID balasan (jika ada) dan ID konsultasi dari request
        $replyId = $request->input('reply_id');
        $consultationId = $request->input('consultation_id');

        // Validasi input dari request
        $request->validate([
            'consultation_id' => 'required|exists:consultations,id',
            'message'         => 'required|string|max:1000',
            'reply_id'        => 'nullable|integer|exists:consultation_replies,id',
        ], [
            'consultation_id.required' => 'ID konsultasi wajib diisi.',
            'consultation_id.exists'   => 'Konsultasi tidak ditemukan.',
            'message.required'         => 'Pesan balasan wajib diisi.',
            'message.string'           => 'Pesan balasan harus berupa teks.',
            'message.max'              => 'Pesan balasan tidak boleh lebih dari 1000 karakter.',
            'reply_id.integer'         => 'ID balasan tidak valid.',
            'reply_id.exists'          => 'Data balasan tidak ditemukan.',
        ]);

        // Jika ada reply_id, artinya update balasan yang sudah ada
        if ($replyId) {
            $reply = ConsultationReply::where('id', $replyId)
                ->where('sender_id', Auth::id()) // hanya pengirim asli yang bisa update
                ->first();

            if (!$reply) {
                return response()->json([
                    'message' => 'Balasan tidak ditemukan atau Anda tidak memiliki izin untuk mengubahnya.'
                ], 403);
            }
        } else {
            // Jika tidak ada reply_id, buat balasan baru
            $reply                  = new ConsultationReply();
            $reply->consultation_id = $consultationId;
            $reply->sender_id       = Auth::id();
        }

        // Isi atau perbarui pesan
        $reply->message = $request->message;
        $reply->save();

        // Kembalikan response JSON
        return response()->json([
            'message' => $replyId
                ? 'Balasan berhasil diperbarui.'
                : 'Balasan berhasil dikirim.',
            'data' => $reply
        ], $replyId ? 200 : 201);
    }

    public function deleteReply($replyId)
    {
        // Ambil data balasan lengkap dengan relasi konsultasi
        $reply = ConsultationReply::with('consultation')->find($replyId);

        // Jika balasan tidak ditemukan
        if (!$reply) {
            return response()->json([
                'message' => 'Balasan tidak ditemukan.'
            ], 404);
        }

        $user = Auth::user();

        // Cek apakah user berhak menghapus (pengirim sendiri atau bidan pemilik konsultasi)
        $isOwner = $reply->sender_id === $user->id;
        $isBidan = $user->role === 'bidan' && $reply->consultation->bidan_id === $user->id;

        if (!$isOwner && !$isBidan) {
            return response()->json([
                'message' => 'Anda tidak memiliki izin untuk menghapus balasan ini.'
            ], 403);
        }

        // Hapus balasan
        $reply->delete();

        return response()->json([
            'message' => 'Balasan berhasil dihapus.'
        ]);
    }
}
