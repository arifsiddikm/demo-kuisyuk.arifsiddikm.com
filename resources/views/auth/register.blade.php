@extends('layouts.app')
@section('title', 'Daftar Gratis — KuisYuk!')

@push('head')
<style>
.auth-page { min-height:100vh; display:flex; }
.auth-left {
    flex:1; background:linear-gradient(135deg,#DC2626,#991b1b);
    display:flex; flex-direction:column; align-items:center; justify-content:center;
    padding:3rem; position:relative; overflow:hidden;
}
.auth-left .b { position:absolute; border-radius:50%; background:rgba(255,255,255,0.08); }
.auth-right { flex:1; display:flex; align-items:center; justify-content:center; padding:2rem; background:#fff5f5; overflow-y:auto; }
.auth-card { background:#fff; border-radius:24px; padding:2.5rem; width:100%; max-width:420px; box-shadow:0 10px 40px rgba(0,0,0,0.08); border:1.5px solid #fee2e2; }
.auth-title { font-family:'Fredoka One',cursive; font-size:1.8rem; color:#1f2937; margin-bottom:0.25rem; }
.auth-sub { color:#6b7280; font-size:0.9rem; font-weight:600; margin-bottom:1.75rem; }
@media(max-width:768px) { .auth-left{display:none;} .auth-right{padding:1.5rem;} }
</style>
@endpush

@section('content')
<div class="auth-page">
    <div class="auth-left">
        <div class="b" style="width:250px;height:250px;top:-80px;right:-80px;"></div>
        <div class="b" style="width:150px;height:150px;bottom:10%;left:-40px;"></div>
        <div style="position:relative;z-index:1;text-align:center;">
            <div style="font-size:4rem;margin-bottom:1rem;">🎉</div>
            <div style="font-family:'Fredoka One',cursive;font-size:2.2rem;color:#fff;margin-bottom:0.75rem;">Bergabung Yuk!</div>
            <div style="color:rgba(255,255,255,0.8);font-size:0.95rem;font-weight:600;line-height:1.7;max-width:260px;">
                Buat akun gratis dan mulai buat kuis pertamamu dalam hitungan menit!
            </div>
            <div style="margin-top:2rem;background:rgba(255,255,255,0.12);border-radius:16px;padding:1.25rem;">
                <div style="color:#fff;font-weight:800;font-size:0.95rem;margin-bottom:0.75rem;">Ini yang kamu dapat:</div>
                <div style="color:rgba(255,255,255,0.85);font-size:0.88rem;font-weight:600;line-height:2;">
                    🎯 Buat kuis tanpa batas<br>
                    🔗 Link pendek unik per kuis<br>
                    📊 Dashboard hasil real-time<br>
                    📥 Export Excel & PDF<br>
                    🆓 100% Gratis selamanya
                </div>
            </div>
        </div>
    </div>

    <div class="auth-right">
        <div class="auth-card">
            <a href="{{ route('home') }}" style="display:inline-flex;align-items:center;gap:6px;color:#9ca3af;font-weight:700;font-size:0.85rem;text-decoration:none;margin-bottom:1.5rem;">
                ← Kembali ke beranda
            </a>
            <div class="auth-title">🚀 Daftar Gratis</div>
            <div class="auth-sub">Buat akun KuisYuk! dalam 30 detik</div>

            @if($errors->any())
                <div class="alert alert-error">
                    <span>⚠️</span>
                    <div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">👤 Nama Lengkap</label>
                    <input type="text" name="name" class="form-input" placeholder="Nama kamu" value="{{ old('name') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">📧 Email</label>
                    <input type="email" name="email" class="form-input" placeholder="kamu@email.com" value="{{ old('email') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">🔒 Password</label>
                    <input type="password" name="password" class="form-input" placeholder="Minimal 6 karakter" required>
                </div>
                <div class="form-group">
                    <label class="form-label">🔒 Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-input" placeholder="Ulangi password" required>
                </div>
                <div style="background:#fff5f5;border-radius:12px;padding:12px;margin-bottom:1.25rem;font-size:0.82rem;color:#6b7280;font-weight:600;border:1.5px solid #fee2e2;">
                    Dengan daftar, kamu setuju dengan syarat & ketentuan KuisYuk! dan menyetujui kebijakan privasi kami.
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:13px;">
                    🎉 Buat Akun Gratis
                </button>
            </form>

            <div style="text-align:center;margin-top:1.5rem;font-size:0.9rem;font-weight:700;color:#6b7280;">
                Sudah punya akun?
                <a href="{{ route('login') }}" style="color:#DC2626;text-decoration:none;font-weight:800;">Masuk di sini</a>
            </div>
        </div>
    </div>
</div>
@endsection
