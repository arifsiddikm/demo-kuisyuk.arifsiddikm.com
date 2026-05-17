@extends('layouts.app')
@section('title', 'Kuis Tidak Tersedia — KuisYuk!')
@section('content')
<div style="min-height:100vh;background:linear-gradient(135deg,#DC2626,#991b1b);display:flex;align-items:center;justify-content:center;padding:2rem;">
    <div style="background:#fff;border-radius:24px;padding:3rem;max-width:440px;width:100%;text-align:center;box-shadow:0 20px 60px rgba(0,0,0,0.2);">
        <div style="font-size:4rem;margin-bottom:1rem;">😴</div>
        <div style="font-family:'Fredoka One',cursive;font-size:1.8rem;color:#1f2937;margin-bottom:0.5rem;">Kuis Sedang Nonaktif</div>
        <div style="color:#6b7280;font-weight:600;font-size:0.95rem;line-height:1.7;margin-bottom:1.75rem;">
            Kuis <strong>{{ $quiz->title }}</strong> saat ini sedang dinonaktifkan oleh kreator.<br>
            Coba hubungi kreator untuk informasi lebih lanjut.
        </div>
        <a href="{{ route('home') }}" class="btn btn-primary" style="justify-content:center;width:100%;">🏠 Kembali ke Beranda</a>
    </div>
</div>
@endsection
