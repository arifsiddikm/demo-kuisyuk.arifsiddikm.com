@extends('layouts.app')
@section('title', 'Kuis Tidak Ditemukan — KuisYuk!')

@section('content')
<div style="min-height:100vh;background:linear-gradient(135deg,#DC2626 0%,#991b1b 60%,#7f1d1d 100%);display:flex;align-items:center;justify-content:center;padding:2rem;position:relative;overflow:hidden;">
    <div style="position:absolute;width:320px;height:320px;border-radius:50%;background:rgba(255,255,255,0.06);top:-100px;right:-100px;pointer-events:none;"></div>
    <div style="position:absolute;width:200px;height:200px;border-radius:50%;background:rgba(255,255,255,0.05);bottom:-60px;left:-60px;pointer-events:none;"></div>

    <div style="background:#fff;border-radius:28px;padding:3rem 2.5rem;max-width:500px;width:100%;text-align:center;box-shadow:0 24px 64px rgba(0,0,0,0.25);position:relative;z-index:1;">
        <div style="font-size:4.5rem;margin-bottom:1rem;animation:shake 0.8s ease 0.3s both;">🔍</div>
        <div style="font-family:'Fredoka One',cursive;font-size:2rem;color:#1f2937;margin-bottom:0.6rem;">Kuis Tidak Ditemukan</div>
        <p style="color:#6b7280;font-weight:600;font-size:0.95rem;line-height:1.75;margin-bottom:1.25rem;">
            Kode kuis <code style="background:#fee2e2;color:#DC2626;padding:2px 8px;border-radius:6px;font-size:0.9rem;font-weight:800;">{{ $slug }}</code> tidak ditemukan.<br>
            Mungkin link yang kamu gunakan salah atau sudah dihapus.
        </p>

        <div style="background:#fff5f5;border-radius:16px;padding:1.25rem;margin-bottom:1.75rem;text-align:left;border:1.5px solid #fee2e2;">
            <div style="font-weight:800;color:#1f2937;font-size:0.9rem;margin-bottom:0.75rem;">💡 Coba lakukan ini:</div>
            <div style="font-size:0.88rem;font-weight:600;color:#374151;line-height:2;">
                ✅ Cek kembali link yang diberikan oleh kreator<br>
                ✅ Pastikan kode kuis disalin dengan lengkap<br>
                ✅ Hubungi kreator atau penyelenggara kuis<br>
                ✅ Coba minta link kuis yang baru
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
@keyframes shake {
    0%,100%{transform:translateX(0);}
    20%{transform:translateX(-8px);}
    40%{transform:translateX(8px);}
    60%{transform:translateX(-5px);}
    80%{transform:translateX(5px);}
}
</style>
@endpush
@endsection
