@extends('layouts.app')
@section('title', 'Link Kuis Tidak Valid — KuisYuk!')

@section('content')
<div style="min-height:100vh;background:linear-gradient(135deg,#DC2626 0%,#991b1b 60%,#7f1d1d 100%);display:flex;align-items:center;justify-content:center;padding:2rem;position:relative;overflow:hidden;">
    <!-- Bubbles -->
    <div style="position:absolute;width:300px;height:300px;border-radius:50%;background:rgba(255,255,255,0.06);top:-80px;right:-80px;pointer-events:none;"></div>
    <div style="position:absolute;width:200px;height:200px;border-radius:50%;background:rgba(255,255,255,0.05);bottom:-60px;left:-60px;pointer-events:none;"></div>

    <div style="background:#fff;border-radius:28px;padding:3rem 2.5rem;max-width:480px;width:100%;text-align:center;box-shadow:0 24px 64px rgba(0,0,0,0.25);position:relative;z-index:1;">
        <div style="font-size:4rem;margin-bottom:1rem;animation:bounce 1.5s ease-in-out infinite;">🔗</div>
        <div style="font-family:'Fredoka One',cursive;font-size:2rem;color:#1f2937;margin-bottom:0.6rem;">Oops! Link Tidak Lengkap</div>
        <p style="color:#6b7280;font-weight:600;font-size:0.95rem;line-height:1.75;margin-bottom:1.75rem;">
            Kamu butuh link kuis yang lengkap untuk bisa mengisi kuis.<br>
            Link kuis yang valid terlihat seperti ini:
        </p>

        <div style="background:#fff5f5;border:2px dashed #fca5a5;border-radius:14px;padding:12px 18px;margin-bottom:1.5rem;font-family:monospace;font-size:0.92rem;color:#DC2626;font-weight:700;">
            {{ url('/kuis') }}/<span style="background:#DC2626;color:#fff;padding:2px 8px;border-radius:6px;">KODE7</span>
        </div>

        <div style="background:#f9fafb;border-radius:16px;padding:1.25rem;margin-bottom:1.75rem;text-align:left;">
            <div style="font-weight:800;color:#1f2937;font-size:0.9rem;margin-bottom:0.75rem;">💡 Cara mendapatkan link kuis:</div>
            <div style="font-size:0.88rem;font-weight:600;color:#374151;line-height:2;">
                1. Minta link dari kreator atau penyelenggara kuis<br>
                2. Biasanya dikirim via WhatsApp, email, atau media sosial<br>
                3. Pastikan kamu menyalin link secara lengkap
            </div>
        </div>

        <div style="display:flex;flex-direction:column;gap:10px;">
            <a href="{{ route('home') }}" class="btn btn-primary" style="justify-content:center;">🏠 Kembali ke Beranda</a>
            @guest
            <a href="{{ route('register') }}" class="btn btn-secondary" style="justify-content:center;">✏️ Buat Kuis Sendiri — Gratis!</a>
            @endguest
        </div>
    </div>
</div>

@push('head')
<style>
@keyframes bounce { 0%,100%{transform:translateY(0);} 50%{transform:translateY(-14px);} }
</style>
@endpush
@endsection
