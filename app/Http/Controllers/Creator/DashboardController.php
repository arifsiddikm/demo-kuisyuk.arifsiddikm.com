<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizResponse;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $totalQuizzes   = $user->quizzes()->count();
        $activeQuizzes  = $user->quizzes()->where('is_active', true)->count();
        $totalResponses = QuizResponse::whereHas('quiz', fn($q) => $q->where('user_id', $user->id))->count();
        $recentQuizzes  = $user->quizzes()->withCount('responses')->latest()->take(5)->get();

        return view('creator.dashboard', compact(
            'totalQuizzes', 'activeQuizzes', 'totalResponses', 'recentQuizzes'
        ));
    }
}
