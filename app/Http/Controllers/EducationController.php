<?php

namespace App\Http\Controllers;

use App\Models\EducationalModule;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class EducationController extends Controller
{
    public function index()
    {
        $materials = EducationalModule::orderBy('id', 'desc')->get();

        return view('education-index', [
            'title'    => 'Materi Edukasi',
            'list'     => $materials,
        ]);
    }

    public function create()
    {
        //
    }

    public function edit($id)
    {
        try {
            $decryptId = Crypt::decrypt($id);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            return redirect()->back()->with('error', 'ID tidak valid.');
        }

        $query = EducationalModule::find($decryptId);

        if (!$query) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        return view('education-index', [
            'title' => 'Materi Edukasi',
            'data'  => $query,
        ]);
    }

    public function store(Request $request)
    {
        // Buatan Untuk Create dan Update
    }

    public function destroy(string $id)
    {
        //
    }
}
