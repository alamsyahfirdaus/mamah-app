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

    // Ambil konsultasi milik user login, baik itu sebagai ibu atau bidan
    $consultations = Consultation::with(['ibu:id,name', 'bidan:id,name'])
        ->where(function ($query) use ($user) {
            if ($user->role === 'ibu') {
                $query->where('user_id', $user->id);
            } elseif ($user->role === 'bidan') {
                $query->where('bidan_id', $user->id);
            }
        })
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
            'last_reply' => $item->reply()->latest()->first()?->message,
        ];
    });

    return response()->json([
        'message' => 'Daftar konsultasi berhasil diambil.',
        'data'    => $data
    ]);
}


    // public function getDaftarBidan()
    // {
    //     // Pastikan hanya ibu yang bisa mengakses
    //     if (Auth::user()->role !== 'ibu') {
    //         return response()->json([
    //             'message' => 'Akses ditolak. Hanya pengguna dengan peran ibu yang dapat melihat daftar bidan.'
    //         ], 403);
    //     }

    //     // Ambil semua user dengan role 'bidan'
    //     $bidans = User::where('role', 'bidan')
    //         ->select('id', 'name', 'email', 'photo') // hanya ambil field yang diperlukan
    //         ->orderBy('name')
    //         ->get();

    //     if ($bidans->isEmpty()) {
    //         return response()->json([
    //             'message' => 'Belum ada bidan yang tersedia.',
    //             'data' => []
    //         ], 404);
    //     }

    //     // Format foto ke URL lengkap
    //     $bidans->transform(function ($bidan) {
    //         $bidan->photo = $bidan->photo
    //             ? URL::to('/') . '/storage/images/' . $bidan->photo
    //             : null;
    //         return $bidan;
    //     });

    //     return response()->json([
    //         'message' => 'Daftar bidan berhasil diambil.',
    //         'data' => $bidans
    //     ]);
    // }

    public function getDaftarPasangan()
    {
        $user = Auth::user();

        // Jika role ibu, ambil daftar bidan
        if ($user->role === 'ibu') {
            $data = User::where('role', 'bidan')
                ->select('id', 'name', 'email', 'photo')
                ->orderBy('name')
                ->get();

            $data->transform(function ($item) {
                $item->photo = $item->photo
                    ? URL::to('/') . '/storage/images/' . $item->photo
                    : null;
                return $item;
            });

            return response()->json([
                'message' => 'Daftar bidan berhasil diambil.',
                'data' => $data
            ]);
        }

        // Jika role bidan, ambil daftar ibu
        if ($user->role === 'bidan') {
            $data = User::where('role', 'ibu')
                ->select('id', 'name', 'email', 'photo')
                ->orderBy('name')
                ->get();

            $data->transform(function ($item) {
                $item->photo = $item->photo
                    ? URL::to('/') . '/storage/images/' . $item->photo
                    : null;
                return $item;
            });

            return response()->json([
                'message' => 'Daftar ibu berhasil diambil.',
                'data' => $data
            ]);
        }

        // Jika bukan ibu atau bidan
        return response()->json([
            'message' => 'Akses ditolak. Hanya ibu atau bidan yang dapat melihat daftar ini.'
        ], 403);
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

 public function store(Request $request)
{
    $user = Auth::user();
    $id = $request->input('id');

    // Validasi tergantung role yang login
    $rules = [
        'id'       => 'nullable|integer|exists:consultations,id',
        'question' => 'required|string|max:1000',
    ];

    if ($user->role === 'ibu') {
        $rules['bidan_id'] = 'required|exists:users,id';
    } elseif ($user->role === 'bidan') {
        $rules['ibu_id'] = 'required|exists:users,id';
    } else {
        return response()->json(['message' => 'Role tidak diizinkan.'], 403);
    }

    $request->validate($rules, [
        'id.integer'        => 'ID konsultasi tidak valid.',
        'id.exists'         => 'Data konsultasi tidak ditemukan.',
        'bidan_id.required' => 'ID tujuan wajib diisi.',
        'bidan_id.exists'   => 'User tidak ditemukan.',
        'ibu_id.required'   => 'ID tujuan wajib diisi.',
        'ibu_id.exists'     => 'User tidak ditemukan.',
        'question.required' => 'Pertanyaan wajib diisi.',
        'question.string'   => 'Pertanyaan harus berupa teks.',
        'question.max'      => 'Pertanyaan terlalu panjang.',
    ]);

    $consultation = $id
        ? Consultation::where('id', $id)->where(function ($q) use ($user) {
            if ($user->role === 'ibu') {
                $q->where('user_id', $user->id);
            } elseif ($user->role === 'bidan') {
                $q->where('bidan_id', $user->id);
            }
        })->first()
        : new Consultation;

    if ($id && !$consultation) {
        return response()->json([
            'message' => 'Konsultasi tidak ditemukan atau tidak diizinkan.'
        ], 404);
    }

    // Atur pasangan sesuai role yang login
    if ($user->role === 'ibu') {
        $consultation->user_id = $user->id;
        $consultation->bidan_id = $request->bidan_id;
    } elseif ($user->role === 'bidan') {
        $consultation->user_id = $request->ibu_id;
        $consultation->bidan_id = $user->id;
    }

    $consultation->topic = $request->question;
    $consultation->save();

    return response()->json([
        'message' => $id ? 'Konsultasi diperbarui.' : 'Konsultasi berhasil dibuat.',
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
                'user_id'    => $consultation->user_id,
                'ibu_id'     => $consultation->ibu->id,
                'ibu'        => $consultation->ibu->name,
                'bidan_id'   => $consultation->bidan->id,
                'bidan'      => $consultation->bidan->name,
                'created_at' => $consultation->created_at->format('Y-m-d H:i'),
                'reply'      => $consultation->reply ?? null
            ]
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
