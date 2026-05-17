@extends('layouts.app')
@section('title', 'Masuk — KuisYuk!')

@push('head')
<style>
.auth-page { min-height:100vh; display:flex; }
.auth-left {
    flex:1; background:linear-gradient(135deg,#DC2626 0%,#991b1b 60%,#7f1d1d 100%);
    display:flex; flex-direction:column; align-items:center; justify-content:center;
    padding:3rem; position:relative; overflow:hidden;
}
.auth-left .b { position:absolute; border-radius:50%; background:rgba(255,255,255,0.08); pointer-events:none; }
.auth-right {
    flex:1; display:flex; align-items:center; justify-content:center;
    padding:2rem; background:#fff5f5; overflow-y:auto;
}
.auth-card {
    background:#fff; border-radius:24px; padding:2.5rem;
    width:100%; max-width:420px;
    box-shadow:0 10px 40px rgba(0,0,0,0.08);
    border:1.5px solid #fee2e2;
}
.auth-title { font-family:'Fredoka One',cursive; font-size:1.8rem; color:#1f2937; margin-bottom:0.25rem; }
.auth-sub { color:#6b7280; font-size:0.9rem; font-weight:600; margin-bottom:1.75rem; }
.auth-divider { display:flex;align-items:center;gap:10px;margin:1.25rem 0; }
.auth-divider hr { flex:1;border:none;border-top:1.5px solid #fee2e2; }
.auth-divider span { color:#9ca3af;font-size:0.82rem;font-weight:700;white-space:nowrap; }
.autofill-btn {
    width:100%; padding:11px; border:2px dashed #fca5a5; border-radius:14px;
    background:#fff5f5; color:#DC2626; font-weight:700; font-size:0.88rem;
    cursor:pointer; font-family:'Nunito',sans-serif; transition:all 0.2s;
    display:flex; align-items:center; justify-content:center; gap:8px;
    margin-bottom:8px;
}
.autofill-btn:hover { background:#fee2e2; border-color:#DC2626; transform:translateY(-2px); }
@keyframes leftFloat { 0%,100%{transform:translateY(0);} 50%{transform:translateY(-12px);} }
.left-float { animation: leftFloat 3.5s ease-in-out infinite; }
@media(max-width:768px) { .auth-left{display:none;} .auth-right{padding:1.5rem;} }
</style>
@endpush

@section('content')
<div class="auth-page">
    <div class="auth-left">
        <div class="b" style="width:280px;height:280px;top:-100px;left:-100px;"></div>
        <div class="b" style="width:160px;height:160px;bottom:8%;right:-50px;"></div>
        <div class="b" style="width:80px;height:80px;top:45%;right:18%;opacity:0.5;"></div>
        <div style="position:relative;z-index:1;text-align:center;" class="left-float">
            <div style="font-size:4.5rem;margin-bottom:1rem;">🎯</div>
            <div style="font-family:'Fredoka One',cursive;font-size:2.6rem;color:#fff;margin-bottom:0.75rem;">KuisYuk!</div>
            <div style="color:rgba(255,255,255,0.82);font-size:1rem;font-weight:600;line-height:1.8;max-width:280px;margin:0 auto 2rem;">
                Platform kuis online paling seru.<br>Buat, bagikan, dan pantau hasilnya!
            </div>
            <div style="display:flex;flex-direction:column;gap:8px;max-width:260px;margin:0 auto;">
                <div style="background:rgba(255,255,255,0.14);border-radius:12px;padding:10px 16px;display:flex;align-items:center;gap:10px;">
                    <span>✅</span><span style="color:rgba(255,255,255,0.9);font-weight:700;font-size:0.88rem;">Gratis selamanya</span>
                </div>
                <div style="background:rgba(255,255,255,0.14);border-radius:12px;padding:10px 16px;display:flex;align-items:center;gap:10px;">
                    <span>✅</span><span style="color:rgba(255,255,255,0.9);font-weight:700;font-size:0.88rem;">Link pendek unik</span>
                </div>
                <div style="background:rgba(255,255,255,0.14);border-radius:12px;padding:10px 16px;display:flex;align-items:center;gap:10px;">
                    <span>✅</span><span style="color:rgba(255,255,255,0.9);font-weight:700;font-size:0.88rem;">Export hasil Excel & PDF</span>
                </div>
            </div>
        </div>
    </div>

    <div class="auth-right">
        <div class="auth-card">
            <a href="{{ route('home') }}" style="display:inline-flex;align-items:center;gap:6px;color:#9ca3af;font-weight:700;font-size:0.85rem;text-decoration:none;margin-bottom:1.5rem;">
                ← Kembali ke beranda
            </a>
            <div class="auth-title">👋 Selamat Datang!</div>
            <div class="auth-sub">Masuk ke akun KuisYuk! kamu</div>

            @if($errors->any())
            <div class="alert alert-error">
                <span>⚠️</span> {{ $errors->first() }}
            </div>
            @endif

            <form action="{{ route('login') }}" method="POST" id="loginForm">
                @csrf
                <div class="form-group">
                    <label class="form-label">📧 Email</label>
                    <input type="email" name="email" id="inputEmail" class="form-input"
                        placeholder="kamu@email.com" value="{{ old('email') }}" required autocomplete="email">
                </div>
                <div class="form-group">
                    <label class="form-label">🔒 Password</label>
                    <input type="password" name="password" id="inputPassword" class="form-input"
                        placeholder="Masukkan password" required autocomplete="current-password">
                </div>
                <div style="display:flex;align-items:center;margin-bottom:1.25rem;">
                    <label class="form-check-label" style="padding:0;border:none;background:none;">
                        <input type="checkbox" name="remember"> <span>Ingat saya</span>
                    </label>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:13px;font-size:1rem;">
                    🚀 Masuk Sekarang
                </button>
            </form>

            <div class="auth-divider"><hr><span>Testing Cepat</span><hr></div>

            <button type="button" class="autofill-btn" onclick="doAutofill('creator@kuisyuk.com','creator123','Kreator Demo')">
                ✏️ Autofill Kreator Demo
            </button>
            <button type="button" class="autofill-btn" onclick="doAutofill('admin@kuisyuk.com','admin123','Admin')">
                👑 Autofill Admin
            </button>

            <div style="text-align:center;margin-top:1.5rem;font-size:0.9rem;font-weight:700;color:#6b7280;">
                Belum punya akun?
                <a href="{{ route('register') }}" style="color:#DC2626;text-decoration:none;font-weight:800;">Daftar Gratis 🎉</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function doAutofill(email, password, label) {
    document.getElementById('inputEmail').value = email;
    document.getElementById('inputPassword').value = password;
    Swal.fire({
        icon: 'info',
        title: 'Autofill ' + label + '!',
        text: 'Email & password sudah diisi. Klik tombol Masuk untuk login.',
        timer: 2500,
        showConfirmButton: false
    });
}
</script>
@endpush
