<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizResponse;
use App\Models\Answer;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function show(string $slug)
    {
        $quiz = Quiz::where('slug', $slug)->with(['questions.options'])->first();

        // Quiz not found
        if (!$quiz) {
            return view('public.quiz-not-found', ['slug' => $slug]);
        }

        // Quiz inactive
        if (!$quiz->is_active) {
            return view('public.quiz-inactive', compact('quiz'));
        }

        return view('public.quiz', compact('quiz'));
    }

    /**
     * Validate primary key uniqueness BEFORE quiz starts (called via AJAX on identity step)
     */
    public function validateIdentity(Request $request, string $slug)
    {
        $quiz = Quiz::where('slug', $slug)->first();

        if (!$quiz || !$quiz->is_active) {
            return response()->json(['valid' => false, 'error' => 'Kuis tidak tersedia.'], 422);
        }

        if (!$quiz->primary_key_enabled || !$quiz->primary_key_unique) {
            return response()->json(['valid' => true]);
        }

        $value = $request->input('primary_key_value');
        if (empty($value)) {
            return response()->json(['valid' => false, 'error' => $quiz->primary_key_label . ' tidak boleh kosong.'], 422);
        }

        $exists = QuizResponse::where('quiz_id', $quiz->id)
            ->where('primary_key_value', $value)
            ->exists();

        if ($exists) {
            return response()->json([
                'valid' => false,
                'error' => $quiz->primary_key_label . ' "' . $value . '" sudah pernah mengisi kuis ini dan tidak dapat mengisi lagi.'
            ], 422);
        }

        return response()->json(['valid' => true]);
    }

    public function submit(Request $request, string $slug)
    {
        $quiz = Quiz::where('slug', $slug)->with(['questions.options'])->first();

        if (!$quiz) {
            return response()->json(['error' => 'Kuis tidak ditemukan.'], 404);
        }

        if (!$quiz->is_active) {
            return response()->json(['error' => 'Kuis tidak aktif.'], 422);
        }

        $request->validate([
            'respondent_name'   => 'required|string|max:255',
            'primary_key_value' => $quiz->primary_key_enabled ? 'required|string|max:255' : 'nullable',
        ]);

        // Double-check unique primary key on submit too
        if ($quiz->primary_key_enabled && $quiz->primary_key_unique) {
            $exists = QuizResponse::where('quiz_id', $quiz->id)
                ->where('primary_key_value', $request->primary_key_value)
                ->exists();
            if ($exists) {
                return response()->json([
                    'error' => $quiz->primary_key_label . ' ini sudah pernah mengisi kuis dan tidak dapat mengisi lagi.'
                ], 422);
            }
        }

        $response = QuizResponse::create([
            'quiz_id'           => $quiz->id,
            'respondent_name'   => $request->respondent_name,
            'primary_key_value' => $request->primary_key_value,
            'ip_address'        => $request->ip(),
            'submitted_at'      => now(),
        ]);

        // Save answers
        $answers = $request->input('answers', []);
        foreach ($quiz->questions as $question) {
            $answerData = [
                'quiz_response_id' => $response->id,
                'question_id'      => $question->id,
            ];

            if ($question->question_type === 'multiple_choice') {
                $optionId = $answers[$question->id] ?? null;
                if ($optionId) {
                    $answerData['option_id'] = $optionId;
                }
            } else {
                $answerData['essay_answer'] = $answers[$question->id] ?? null;
            }

            Answer::create($answerData);
        }

        return response()->json(['success' => true, 'message' => 'Jawaban berhasil dikirim! Terima kasih 🎉']);
    }
}
