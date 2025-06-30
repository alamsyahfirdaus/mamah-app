<?php

namespace App\Http\Controllers;

use App\Models\SupportGroup;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class DiscussionController extends Controller
{
    public function index()
    {
        $groups = SupportGroup::with('creator')
            ->orderByDesc('id')
            ->get();

        return view('discussion-index', [
            'title' => 'Interaksi Ibu',
            'list'  => $groups,
        ]);
    }

    public function create()
    {
        return view('discussion-index', [
            'title' => 'Interaksi Ibu',
        ]);
    }

    public function edit($id)
    {
        try {
            $groupId = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'ID tidak valid.');
        }

        $group = SupportGroup::find($groupId);

        if (!$group) {
            return redirect()->back()->with('error', 'Data grup tidak ditemukan.');
        }

        return view('discussion-index', [
            'title' => 'Interaksi Ibu',
            'data'  => $group,
        ]);
    }

    public function store(Request $request)
    {
        $groupId = null;

        if ($request->filled('id')) {
            try {
                $groupId = Crypt::decrypt($request->id);
            } catch (DecryptException $e) {
                return redirect()->back()->with('error', 'ID tidak valid.');
            }
        }

        $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
        ], [
            'name.required'   => 'Nama grup wajib diisi.',
        ]);

        $group = $groupId ? SupportGroup::findOrFail($groupId) : new SupportGroup();

        $group->name        = $request->name;
        $group->description = preg_replace('/\s+/', ' ', trim($request->description));
        $group->created_by  = $groupId ? $group->created_by : Auth::id();

        $group->save();

        $message = $groupId ? 'Grup diskusi berhasil diperbarui.' : 'Grup diskusi berhasil ditambahkan.';

        return redirect()->route('discussion.index')->with('success', $message);
    }

    public function destroy($id)
    {
        try {
            $groupId = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'ID tidak valid.');
        }

        $group = SupportGroup::find($groupId);

        if (!$group) {
            return redirect()->back()->with('error', 'Grup diskusi tidak ditemukan.');
        }

        $group->delete();

        return redirect()->back()->with('success', 'Grup diskusi berhasil dihapus.');
    }
}
