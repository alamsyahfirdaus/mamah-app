<?php

namespace App\Http\Controllers;

use App\Models\Interpretation;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class InterpretationController extends Controller
{

    public function index()
    {
        $list = Interpretation::orderBy('created_at', 'desc')->get();

        return view('interpretation-index', [
            'title' => 'Interpretasi',
            'list'  => $list,
        ]);
    }

    public function create()
    {
        return view('interpretation-index', [
            'title' => 'Interpretasi',
        ]);
    }

    public function edit($id)
    {
        try {
            $decryptId = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'ID tidak valid.');
        }

        $query = Interpretation::find($decryptId);

        if (!$query) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        return view('interpretation-index', [
            'title' => 'Interpretasi',
            'data'  => $query,
        ]);
    }

    public function store(Request $request)
    {
        $interpretationId = null;

        // Jika ada input 'id', berarti update
        if ($request->filled('id')) {
            try {
                $interpretationId = Crypt::decrypt($request->id);
            } catch (DecryptException $e) {
                return redirect()->back()->with('error', 'ID tidak valid.');
            }
        }

        // Validasi
        $request->validate([
            'min_score'      => 'required|numeric',
            'max_score'      => 'required|numeric|gte:min_score',
            'category'       => 'required|string|max:255',
            'recommendation' => 'required|string',
        ], [
            'min_score.required'      => 'Skor minimal wajib diisi.',
            'min_score.numeric'       => 'Skor minimal harus berupa angka.',
            'max_score.required'      => 'Skor maksimal wajib diisi.',
            'max_score.numeric'       => 'Skor maksimal harus berupa angka.',
            'max_score.gte'           => 'Skor maksimal harus lebih besar atau sama dengan skor minimal.',
            'category.required'       => 'Kategori wajib diisi.',
            'category.string'         => 'Kategori harus berupa teks.',
            'category.max'            => 'Kategori maksimal 255 karakter.',
            'recommendation.required' => 'Interpretasi wajib diisi.',
            'recommendation.string'   => 'Interpretasi harus berupa teks.',
        ]);

        // Ambil data lama jika update atau buat baru
        $interpretation = $interpretationId ? Interpretation::findOrFail($interpretationId) : new Interpretation();

        $interpretation->min_score      = $request->min_score;
        $interpretation->max_score      = $request->max_score;
        $interpretation->category       = $request->category;
        $interpretation->recommendation = $request->recommendation;

        $interpretation->save();

        $message = $interpretationId ? 'Interpretasi berhasil diperbarui.' : 'Interpretasi berhasil ditambahkan.';
        return redirect()->route('interpretation.index')->with('success', $message);
    }

    public function destroy(string $id)
    {
        try {
            $decryptId = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'ID tidak valid.');
        }

        $interpretation = Interpretation::find($decryptId);

        if (!$interpretation) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        $interpretation->delete();

        return redirect()->route('interpretation.index')->with('success', 'Interpretasi berhasil dihapus.');
    }
}
