<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class QuizController extends Controller
{
    /** Check if quiz belongs to current user, abort 403 if not */
    private function checkOwner(Quiz $quiz): void
    {
        if ($quiz->user_id !== Auth::id()) {
            abort(403, 'Kamu tidak punya akses ke kuis ini.');
        }
    }

    public function index()
    {
        $quizzes = Auth::user()->quizzes()
            ->withCount(['responses', 'questions'])
            ->latest()
            ->paginate(12);
        return view('creator.quizzes.index', compact('quizzes'));
    }

    public function create()
    {
        return view('creator.quizzes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'             => 'required|string|max:255',
            'description'       => 'nullable|string',
            'cover_image'       => 'nullable|image|max:2048',
            'primary_key_label' => 'nullable|string|max:100',
            'time_limit'        => 'nullable|integer|min:1|max:300',
        ]);

        $data                      = $request->only(['title', 'description', 'primary_key_label', 'time_limit']);
        $data['user_id']           = Auth::id();
        $data['primary_key_enabled'] = $request->boolean('primary_key_enabled');
        $data['primary_key_unique']  = $request->boolean('primary_key_unique');

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        $quiz = Quiz::create($data);

        return redirect()->route('creator.quizzes.questions', $quiz->id)
            ->with('success', 'Kuis berhasil dibuat! Sekarang tambahkan soal. 🎉');
    }

    public function edit(Quiz $quiz)
    {
        $this->checkOwner($quiz);
        return view('creator.quizzes.edit', compact('quiz'));
    }

    public function update(Request $request, Quiz $quiz)
    {
        $this->checkOwner($quiz);
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|max:2048',
            'time_limit'  => 'nullable|integer|min:1|max:300',
        ]);

        $data                      = $request->only(['title', 'description', 'primary_key_label', 'time_limit']);
        $data['primary_key_enabled'] = $request->boolean('primary_key_enabled');
        $data['primary_key_unique']  = $request->boolean('primary_key_unique');

        if ($request->hasFile('cover_image')) {
            if ($quiz->cover_image) Storage::disk('public')->delete($quiz->cover_image);
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        $quiz->update($data);
        return back()->with('success', 'Kuis berhasil diperbarui! ✅');
    }

    public function destroy(Quiz $quiz)
    {
        $this->checkOwner($quiz);
        $quiz->delete();
        return redirect()->route('creator.quizzes.index')
            ->with('success', 'Kuis berhasil dihapus.');
    }

    public function toggleActive(Quiz $quiz)
    {
        $this->checkOwner($quiz);
        $quiz->update(['is_active' => !$quiz->is_active]);
        $msg = $quiz->is_active ? 'Kuis diaktifkan! ✅' : 'Kuis dinonaktifkan. ⏸️';
        return back()->with('success', $msg);
    }

    /* ====== QUESTIONS ====== */

    public function questions(Quiz $quiz)
    {
        $this->checkOwner($quiz);
        $questions = $quiz->questions()->with('options')->orderBy('order')->get();
        return view('creator.quizzes.questions', compact('quiz', 'questions'));
    }

    public function storeQuestion(Request $request, Quiz $quiz)
    {
        $this->checkOwner($quiz);

        $request->validate([
            'question_text'  => 'required|string',
            'question_type'  => 'required|in:multiple_choice,essay',
            'question_image' => 'nullable|image|max:2048',
            'options'        => 'required_if:question_type,multiple_choice|array|min:2',
            'options.*'      => 'nullable|string|max:500',
            'is_correct'     => 'required_if:question_type,multiple_choice',
        ]);

        $questionData = [
            'quiz_id'       => $quiz->id,
            'question_text' => $request->question_text,
            'question_type' => $request->question_type,
            'order'         => $quiz->questions()->count() + 1,
        ];

        if ($request->hasFile('question_image')) {
            $questionData['question_image'] = $request->file('question_image')
                ->store('questions', 'public');
        }

        $question = Question::create($questionData);

        if ($request->question_type === 'multiple_choice' && $request->options) {
            $correctIndex = (int) $request->is_correct;
            $order        = 0;
            foreach ($request->options as $i => $optionText) {
                if (empty(trim($optionText))) continue;
                Option::create([
                    'question_id' => $question->id,
                    'option_text' => $optionText,
                    'is_correct'  => ($i === $correctIndex),
                    'order'       => $order++,
                ]);
            }
        }

        return back()->with('success', 'Soal berhasil ditambahkan! 🎯');
    }

    public function destroyQuestion(Question $question)
    {
        // Make sure this question's quiz belongs to the current user
        if ($question->quiz->user_id !== Auth::id()) {
            abort(403);
        }
        $quizId = $question->quiz_id;
        $question->delete();

        // Re-order remaining questions
        $quiz = Quiz::find($quizId);
        if ($quiz) {
            $quiz->questions()->orderBy('order')->get()->each(function ($q, $idx) {
                $q->update(['order' => $idx + 1]);
            });
        }

        return back()->with('success', 'Soal dihapus.');
    }

    /* ====== RESULTS ====== */

    public function results(Quiz $quiz)
    {
        $this->checkOwner($quiz);
        $responses = $quiz->responses()
            ->with(['answers.question', 'answers.option'])
            ->latest()
            ->paginate(20);
        return view('creator.quizzes.results', compact('quiz', 'responses'));
    }

    public function exportExcel(Quiz $quiz)
    {
        $this->checkOwner($quiz);
        $responses = $quiz->responses()->with(['answers.question', 'answers.option'])->get();
        $questions = $quiz->questions()->with('options')->orderBy('order')->get();

        $filename = 'hasil-' . $quiz->slug . '-' . now()->format('Ymd') . '.csv';
        $headers  = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($responses, $questions, $quiz) {
            $handle = fopen('php://output', 'w');
            // BOM for Excel UTF-8
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            $row = ['No', 'Nama'];
            if ($quiz->primary_key_enabled) $row[] = $quiz->primary_key_label;
            $row[] = 'Waktu Submit';
            foreach ($questions as $q) {
                $row[] = 'S' . $q->order . ': ' . mb_substr(strip_tags($q->question_text), 0, 50);
            }
            fputcsv($handle, $row);

            foreach ($responses as $i => $response) {
                $r = [$i + 1, $response->respondent_name];
                if ($quiz->primary_key_enabled) $r[] = $response->primary_key_value ?? '-';
                $r[] = $response->submitted_at?->format('d/m/Y H:i') ?? '-';

                $answersMap = $response->answers->keyBy('question_id');
                foreach ($questions as $q) {
                    $answer = $answersMap->get($q->id);
                    if (!$answer)                              $r[] = '-';
                    elseif ($q->question_type === 'essay')    $r[] = $answer->essay_answer ?? '-';
                    else                                       $r[] = $answer->option?->option_text ?? '-';
                }
                fputcsv($handle, $r);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
