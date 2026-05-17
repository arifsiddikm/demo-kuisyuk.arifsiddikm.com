@extends('layouts.dashboard')
@section('title', 'Dashboard Kreator')
@section('page-title', 'Dashboard')
@section('breadcrumb', 'KuisYuk! / Dashboard')

@section('content')
<!-- Greeting -->
<div style="background:linear-gradient(135deg,#DC2626,#b91c1c);border-radius:20px;padding:1.75rem 2rem;margin-bottom:1.75rem;position:relative;overflow:hidden;">
    <div style="position:absolute;top:-40px;right:-40px;width:160px;height:160px;border-radius:50%;background:rgba(255,255,255,0.08);"></div>
    <div style="position:absolute;bottom:-30px;right:100px;width:100px;height:100px;border-radius:50%;background:rgba(255,255,255,0.06);"></div>
    <div style="position:relative;z-index:1;">
        <div style="font-family:'Fredoka One',cursive;font-size:1.5rem;color:#fff;margin-bottom:0.25rem;">
            Halo, {{ auth()->user()->name }}! 👋
        </div>
        <div style="color:rgba(255,255,255,0.8);font-weight:600;font-size:0.92rem;">
            Semangat bikin kuis hari ini! Kamu sudah membuat {{ $totalQuizzes }} kuis.
        </div>
    </div>
</div>

<!-- Stats -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1.25rem;margin-bottom:1.75rem;">
    <div class="stat-card fade-in-up">
        <div class="stat-icon" style="background:linear-gradient(135deg,#fee2e2,#fecaca);">📋</div>
        <div>
            <div class="stat-value">{{ $totalQuizzes }}</div>
            <div class="stat-label">Total Kuis</div>
        </div>
    </div>
    <div class="stat-card fade-in-up" style="animation-delay:0.1s;">
        <div class="stat-icon" style="background:linear-gradient(135deg,#dcfce7,#bbf7d0);">✅</div>
        <div>
            <div class="stat-value">{{ $activeQuizzes }}</div>
            <div class="stat-label">Kuis Aktif</div>
        </div>
    </div>
    <div class="stat-card fade-in-up" style="animation-delay:0.2s;">
        <div class="stat-icon" style="background:linear-gradient(135deg,#dbeafe,#bfdbfe);">✏️</div>
        <div>
            <div class="stat-value">{{ $totalResponses }}</div>
            <div class="stat-label">Total Jawaban</div>
        </div>
    </div>
    <div class="stat-card fade-in-up" style="animation-delay:0.3s;">
        <div class="stat-icon" style="background:linear-gradient(135deg,#f3e8ff,#e9d5ff);">🔗</div>
        <div>
            <div class="stat-value">{{ $totalQuizzes - $activeQuizzes }}</div>
            <div class="stat-label">Kuis Nonaktif</div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem;margin-bottom:1.5rem;">
    <!-- Recent Quizzes -->
    <div class="card">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;">
            <h3 style="font-weight:800;color:#1f2937;font-size:1rem;">📋 Kuis Terbaru</h3>
            <a href="{{ route('creator.quizzes.index') }}" class="btn btn-secondary btn-sm">Lihat Semua</a>
        </div>

        @forelse($recentQuizzes as $quiz)
        <div style="display:flex;align-items:center;gap:12px;padding:12px 0;border-bottom:1.5px solid #fee2e2;">
            <div style="width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,#DC2626,#b91c1c);display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0;">
                🎯
            </div>
            <div style="flex:1;min-width:0;">
                <div style="font-weight:800;color:#1f2937;font-size:0.9rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $quiz->title }}</div>
                <div style="font-size:0.78rem;color:#9ca3af;font-weight:600;">
                    {{ $quiz->responses_count }} jawaban · {{ $quiz->created_at->diffForHumans() }}
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:6px;flex-shrink:0;">
                <span class="badge {{ $quiz->is_active ? 'badge-green' : 'badge-red' }}">
                    {{ $quiz->is_active ? '✅ Aktif' : '⏸️ Nonaktif' }}
                </span>
                <a href="{{ route('creator.quizzes.questions', $quiz->id) }}" class="btn btn-secondary btn-sm">Edit</a>
            </div>
        </div>
        @empty
        <div style="text-align:center;padding:2rem;color:#9ca3af;">
            <div style="font-size:2.5rem;margin-bottom:0.75rem;">📭</div>
            <div style="font-weight:700;">Belum ada kuis</div>
            <a href="{{ route('creator.quizzes.create') }}" class="btn btn-primary btn-sm" style="margin-top:0.75rem;">Buat Kuis Pertama</a>
        </div>
        @endforelse
    </div>

    <!-- Quick Actions Panel -->
    <div style="display:flex;flex-direction:column;gap:1rem;">
        <div class="card" style="text-align:center;padding:1.75rem;">
            <div style="font-size:2.5rem;margin-bottom:0.75rem;">➕</div>
            <div style="font-weight:800;color:#1f2937;margin-bottom:0.5rem;">Buat Kuis Baru</div>
            <div style="color:#6b7280;font-size:0.82rem;font-weight:600;margin-bottom:1rem;">Buat kuis baru dan mulai kumpulkan jawaban</div>
            <a href="{{ route('creator.quizzes.create') }}" class="btn btn-primary" style="width:100%;justify-content:center;">🚀 Buat Sekarang</a>
        </div>
        <div class="card" style="text-align:center;padding:1.5rem;">
            <div style="font-size:2rem;margin-bottom:0.75rem;">📊</div>
            <div style="font-weight:800;color:#1f2937;margin-bottom:0.5rem;">Lihat Semua Hasil</div>
            <a href="{{ route('creator.quizzes.index') }}" class="btn btn-secondary" style="width:100%;justify-content:center;">Buka Daftar Kuis</a>
        </div>
    </div>
</div>
@endsection
