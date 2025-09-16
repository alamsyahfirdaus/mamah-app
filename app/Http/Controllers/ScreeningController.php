<?php

namespace App\Http\Controllers;

use App\Models\ScreeningChoice;
use App\Models\ScreeningLevel;
use App\Models\ScreeningQuestion;
use App\Models\ScreeningResult;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class ScreeningController extends Controller
{
    public function index()
    {
        $questions = ScreeningQuestion::with(['choices' => function ($query) {
            $query->orderBy('score', 'asc');
        }])
            ->orderBy('question_no', 'asc')
            ->get();

        return view('question-index', [
            'title' => 'Pertanyaan',
            'list'  => $questions,
        ]);
    }

    public function create()
    {
        return view('question-index', [
            'title' => 'Pertanyaan',
        ]);
    }

    public function edit($id)
    {
        try {
            $decryptId = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'ID tidak valid.');
        }

        $query = ScreeningQuestion::with('choices')->find($decryptId);

        if (!$query) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        return view('question-index', [
            'title' => 'Pertanyaan',
            'data'  => $query,
        ]);
    }

    public function store(Request $request)
    {
        $questionId = null;

        if ($request->filled('id')) {
            try {
                $questionId = Crypt::decrypt($request->id);
            } catch (DecryptException $e) {
                return redirect()->back()->with('error', 'ID tidak valid.');
            }
        }

        $request->validate([
            'question_text' => 'required|string|max:255',
            'label'         => 'required|array|size:4',
            'label.*'       => 'required|string|max:255',
            'score'         => 'required|array|size:4',
            'score.*'       => 'required|integer',
        ], [
            'question_text.required' => 'Pertanyaan wajib diisi.',
            'question_text.string'   => 'Pertanyaan harus berupa teks.',
            'question_text.max'      => 'Pertanyaan tidak boleh lebih dari :max karakter.',

            'label.required'         => 'Seluruh isian jawaban wajib disediakan.',
            'label.array'            => 'Format jawaban tidak valid.',
            'label.size'             => 'Harus terdapat tepat 4 jawaban.',
            'label.*.required'       => 'Setiap jawaban tidak boleh kosong.',
            'label.*.string'         => 'Setiap jawaban harus berupa teks.',
            'label.*.max'            => 'Jawaban tidak boleh lebih dari :max karakter.',

            'score.required'         => 'Nilai skor wajib diisi.',
            'score.array'            => 'Format skor tidak valid.',
            'score.size'             => 'Harus terdapat tepat 4 nilai skor.',
            'score.*.required'       => 'Setiap skor wajib diisi.',
            'score.*.integer'        => 'Setiap skor harus berupa angka.',
        ]);

        $question = $questionId
            ? ScreeningQuestion::findOrFail($questionId)
            : new ScreeningQuestion();

        $question->question_text = $request->question_text;

        if (!$questionId) {
            $question->question_no = ScreeningQuestion::max('question_no') + 1;
        }
        $question->save();

        if ($questionId) {
            $question->choices()->delete();
        }

        foreach ($request->label as $i => $label) {
            $choice = new ScreeningChoice();
            $choice->question_id = $question->id;
            $choice->label = $label;
            $choice->score = $request->score[$i];
            $choice->save();
        }

        $message = $questionId
            ? 'Pertanyaan berhasil diperbarui.'
            : 'Pertanyaan berhasil ditambahkan.';

        return redirect()->route('screening.index')->with('success', $message);
    }

    public function destroy($id)
    {
        try {
            $questionId = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'ID tidak valid.');
        }

        $question = ScreeningQuestion::find($questionId);

        if (!$question) {
            return redirect()->back()->with('error', 'Pertanyaan tidak ditemukan.');
        }

        $question->delete();

        return redirect()->route('screening.index')->with('success', 'Pertanyaan berhasil dihapus.');
    }

    public function reorder($encryptedId, $direction)
    {
        try {
            $current = ScreeningQuestion::findOrFail(Crypt::decrypt($encryptedId));
        } catch (DecryptException | \Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'ID tidak valid atau pertanyaan tidak ditemukan.');
        }

        if ($direction === 'up') {
            $target = ScreeningQuestion::where('question_no', '<', $current->question_no)
                ->orderBy('question_no', 'desc')->first();
            $message = 'Pertanyaan berhasil dinaikkan.';
        } elseif ($direction === 'down') {
            $target = ScreeningQuestion::where('question_no', '>', $current->question_no)
                ->orderBy('question_no', 'asc')->first();
            $message = 'Pertanyaan berhasil diturunkan.';
        } else {
            return redirect()->back()->with('error', 'Arah perpindahan tidak valid.');
        }

        if (!$target) {
            return redirect()->back()->with('error', 'Pertanyaan tujuan tidak ditemukan.');
        }

        DB::transaction(function () use ($current, $target) {
            $temp = ScreeningQuestion::max('question_no') + ScreeningQuestion::count();
            $originalCurrentNo = $current->question_no;
            $originalTargetNo  = $target->question_no;

            $current->question_no = $temp;
            $current->save();

            $target->question_no = $originalCurrentNo;
            $target->save();

            $current->question_no = $originalTargetNo;
            $current->save();
        });

        return redirect()->back()->with('success', $message);
    }

    public function screeningResult()
    {
        $results = ScreeningResult::with('user')->orderBy('id', 'desc')->get();

        return view('screening-result', [
            'title' => 'Skrining',
            'list'  => $results,
        ]);
    }

    public function updateSpecial($id)
    {
        try {
            $question = ScreeningQuestion::findOrFail(Crypt::decrypt($id));
        } catch (DecryptException | \Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'ID tidak valid atau pertanyaan tidak ditemukan.');
        }

        ScreeningQuestion::where('is_special', true)
            ->where('id', '!=', $question->id)
            ->update(['is_special' => false]);

        $question->is_special = true;
        $question->save();

        ScreeningLevel::whereNotNull('question_id')
            ->where('question_id', '!=', $question->id)
            ->update(['question_id' => $question->id]);

        return redirect()->back()->with('success', 'Pertanyaan berhasil ditandai sebagai khusus.');
    }
}
