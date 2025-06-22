<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SupportGroup;
use App\Models\SupportGroupMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SupportGroupController extends Controller
{
    // Menampilkan semua grup dukungan

    public function index()
    {
        $groups = SupportGroup::with('creator:id,name')
            ->orderBy('created_at','desc')
            ->get()
            ->map(function($group) {
                $last = SupportGroupMessage::where('group_id', $group->id)
                    ->latest()
                    ->first();
                return [
                    'id'            => $group->id,
                    'name'          => $group->name,
                    'description'   => $group->description,
                    'creator'       => $group->creator,
                    'last_message'  => $last ? [
                        'text'       => $last->message,
                        'sender'     => $last->user->name,
                        'timestamp'  => $last->created_at->toDateTimeString(),
                    ] : null,
                ];
            });

        return response()->json([
            'message' => 'Daftar grup berhasil diambil.',
            'data'    => $groups
        ]);
    }


    // Membuat atau memperbarui grup dukungan
    public function store(Request $request)
    {
        $groupId = $request->input('id'); // Ambil ID grup dari request
        $group = $groupId ? SupportGroup::find($groupId) : new SupportGroup; // Ambil grup jika update

        // Validasi input
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('support_groups', 'name')->ignore($group->id),
            ],
            'description' => 'nullable|string',
        ], [
            'name.required'      => 'Nama grup wajib diisi.',
            'name.string'        => 'Nama grup harus berupa teks.',
            'name.max'           => 'Nama grup tidak boleh lebih dari 100 karakter.',
            'name.unique'        => 'Nama grup sudah digunakan.',
            'description.string' => 'Deskripsi harus berupa teks.',
        ]);

        // Set data grup
        $group->name = $request->name;
        $group->description = $request->description;

        // Jika grup baru, set pembuat
        if (!$groupId || !$group->exists) {
            $group->created_by = Auth::id();
        }

        $group->save(); // Simpan ke DB

        return response()->json([
            'message' => $groupId ? 'Grup berhasil diperbarui.' : 'Grup berhasil dibuat.',
            'data'    => $group
        ], $groupId ? 200 : 201);
    }

    // Menampilkan detail grup berdasarkan ID
    public function show($id)
    {
        $group = SupportGroup::with('creator:id,name')->find($id); // Ambil grup beserta pembuatnya

        if (!$group) {
            return response()->json([
                'message' => 'Grup tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'message' => 'Detail grup berhasil diambil.',
            'data'    => $group
        ]);
    }

    // Menghapus grup beserta semua pesannya
    public function destroyGroup($id)
    {
        $group = SupportGroup::find($id); // Cari grup berdasarkan ID

        if (!$group) {
            return response()->json([
                'message' => 'Grup tidak ditemukan.'
            ], 404);
        }

        // Pastikan user adalah pembuat grup
        if ($group->created_by !== Auth::id()) {
            return response()->json([
                'message' => 'Anda tidak memiliki izin untuk menghapus grup ini.'
            ], 403);
        }

        SupportGroupMessage::where('group_id', $id)->delete(); // Hapus pesan terkait
        $group->delete(); // Hapus grup

        return response()->json([
            'message' => 'Grup dan seluruh pesan berhasil dihapus.'
        ]);
    }

    // Mengirim atau mengubah pesan dalam grup
    public function sendMessage(Request $request, $groupId)
    {
        $messageId = $request->input('id'); // Ambil ID pesan jika update

        // Validasi pesan
        $request->validate([
            'message' => 'required|string|max:1000',
        ], [
            'message.required' => 'Pesan tidak boleh kosong.',
            'message.string'   => 'Pesan harus berupa teks.',
            'message.max'      => 'Pesan tidak boleh lebih dari 1000 karakter.',
        ]);

        // Pastikan grup ada
        $group = SupportGroup::find($groupId);
        if (!$group) {
            return response()->json([
                'message' => 'Grup tidak ditemukan.'
            ], 404);
        }

        // Jika update pesan
        $message = $messageId
            ? SupportGroupMessage::where('id', $messageId)
            ->where('user_id', Auth::id()) // Hanya pemilik pesan
            ->first()
            : new SupportGroupMessage;

        if ($messageId && !$message) {
            return response()->json([
                'message' => 'Pesan tidak ditemukan atau Anda tidak memiliki izin.'
            ], 404);
        }

        // Set data pesan
        $message->group_id = $groupId;
        $message->user_id  = Auth::id();
        $message->message  = $request->message;
        $message->save(); // Simpan ke DB

        $message->load('user:id,name');

        return response()->json([
            'message' => $messageId ? 'Pesan berhasil diperbarui.' : 'Pesan berhasil dikirim.',
            'data'    => $message
        ], $messageId ? 200 : 201);
    }

    // Menampilkan semua pesan dalam grup
    public function messages($id)
    {
        $group = SupportGroup::find($id); // Pastikan grup ada

        if (!$group) {
            return response()->json([
                'message' => 'Grup tidak ditemukan.'
            ], 404);
        }

        // Ambil semua pesan beserta nama user pengirim
        $messages = SupportGroupMessage::with('user:id,name')
            ->where('group_id', $id)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'message' => 'Daftar pesan berhasil diambil.',
            'data'    => $messages
        ]);
    }

    // Menghapus pesan tunggal
    public function deleteMessage($groupId, $messageId)
    {
        // Cari pesan berdasarkan ID dan milik user login
        $message = SupportGroupMessage::where('id', $messageId)
            ->where('group_id', $groupId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$message) {
            return response()->json([
                'message' => 'Pesan tidak ditemukan atau Anda tidak memiliki izin.'
            ], 404);
        }

        $message->delete(); // Hapus pesan

        return response()->json([
            'message' => 'Pesan berhasil dihapus.'
        ]);
    }
}
