<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Creator\DashboardController;
use App\Http\Controllers\Creator\QuizController;
use App\Http\Controllers\Creator\ProfileController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Public\QuizController as PublicQuizController;

/* ============================================================
   PUBLIC ROUTES
   ============================================================ */

Route::get('/', fn() => view('public.home'))->name('home');

// Info page when someone visits /kuis without a slug
Route::get('/kuis', fn() => view('public.quiz-info'))->name('public.quiz.info');

// Public Quiz (must come after /kuis so static segment wins)
Route::get('/kuis/{slug}', [PublicQuizController::class, 'show'])->name('public.quiz.show');
Route::post('/kuis/{slug}/submit', [PublicQuizController::class, 'submit'])->name('public.quiz.submit');
Route::post('/kuis/{slug}/validate-identity', [PublicQuizController::class, 'validateIdentity'])->name('public.quiz.validate');

/* ============================================================
   AUTH ROUTES
   ============================================================ */

Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/* ============================================================
   CREATOR ROUTES
   ============================================================ */

Route::middleware(['auth', 'role:creator'])
    ->prefix('dashboard')
    ->name('creator.')
    ->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Profile — GET shows view, POST handles update (blade sends @method('PUT') but route accepts POST)
    Route::get('/profile',  fn() => view('creator.profile'))->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Also support PUT directly (for API/fetch calls)
    Route::put('/profile',  [ProfileController::class, 'update'])->name('profile.update.put');

    /* -- Quizzes CRUD -- */
    Route::get('/kuis',           [QuizController::class, 'index'])->name('quizzes.index');
    Route::get('/kuis/buat',      [QuizController::class, 'create'])->name('quizzes.create');
    Route::post('/kuis',          [QuizController::class, 'store'])->name('quizzes.store');
    Route::get('/kuis/{quiz}/edit',   [QuizController::class, 'edit'])->name('quizzes.edit');
    Route::post('/kuis/{quiz}',       [QuizController::class, 'update'])->name('quizzes.update');
    Route::post('/kuis/{quiz}/delete',[QuizController::class, 'destroy'])->name('quizzes.destroy');
    Route::post('/kuis/{quiz}/toggle',[QuizController::class, 'toggleActive'])->name('quizzes.toggle');

    /* -- Questions -- */
    Route::get('/kuis/{quiz}/soal',  [QuizController::class, 'questions'])->name('quizzes.questions');
    Route::post('/kuis/{quiz}/soal', [QuizController::class, 'storeQuestion'])->name('quizzes.questions.store');
    Route::post('/soal/{question}/delete', [QuizController::class, 'destroyQuestion'])->name('questions.destroy');

    /* -- Results -- */
    Route::get('/kuis/{quiz}/hasil',  [QuizController::class, 'results'])->name('quizzes.results');
    Route::get('/kuis/{quiz}/export', [QuizController::class, 'exportExcel'])->name('quizzes.export');
});

/* ============================================================
   ADMIN ROUTES
   ============================================================ */

Route::middleware(['auth', 'role:admin'])
    ->prefix('webmin')
    ->name('admin.')
    ->group(function () {

    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // Users
    Route::get('/pengguna',                   [AdminController::class, 'users'])->name('users');
    Route::post('/pengguna/{user}/toggle',    [AdminController::class, 'toggleUser'])->name('users.toggle');
    Route::post('/pengguna/{user}/delete',    [AdminController::class, 'destroyUser'])->name('users.destroy');
    Route::get('/pengguna/export',            [AdminController::class, 'exportUsers'])->name('users.export');

    // Admins
    Route::get('/admin',              [AdminController::class, 'admins'])->name('admins');
    Route::post('/admin',             [AdminController::class, 'storeAdmin'])->name('admins.store');
    Route::post('/admin/{user}/delete',[AdminController::class, 'destroyAdmin'])->name('admins.destroy');
});
