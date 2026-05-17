@extends('layouts.dashboard')
@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')
@section('breadcrumb', 'KuisYuk! / Profil')

@section('content')
<div style="max-width:600px;">
    <div class="card">
        <!-- Profile Header -->
        <div style="display:flex;align-items:center;gap:1.25rem;padding-bottom:1.5rem;border-bottom:1.5px solid #fee2e2;margin-bottom:1.5rem;">
            <div style="width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,#DC2626,#b91c1c);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:900;font-size:1.8rem;box-shadow:0 6px 20px rgba(220,38,38,0.3);">
                {{ strtoupper(substr(auth()->user()->name,0,1)) }}
            </div>
            <div>
                <div style="font-family:'Fredoka One',cursive;font-size:1.4rem;color:#1f2937;">{{ auth()->user()->name }}</div>
                <div style="color:#9ca3af;font-weight:600;font-size:0.88rem;">{{ auth()->user()->email }}</div>
                <span class="badge badge-red" style="margin-top:4px;">✏️ Kreator</span>
            </div>
        </div>

        @if($errors->any())
        <div class="alert alert-error">
            <span>⚠️</span>
            <div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
        </div>
        @endif

        <form action="{{ route('creator.profile.update') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label">👤 Nama Lengkap</label>
                <input type="text" name="name" class="form-input" value="{{ auth()->user()->name }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">📧 Email</label>
                <input type="email" name="email" class="form-input" value="{{ auth()->user()->email }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">📄 Bio</label>
                <textarea name="bio" class="form-textarea" placeholder="Ceritakan sedikit tentang dirimu..." rows="3">{{ auth()->user()->bio }}</textarea>
            </div>

            <div style="border-top:1.5px solid #fee2e2;padding-top:1.25rem;margin-top:0.5rem;">
                <div style="font-weight:800;color:#1f2937;margin-bottom:0.75rem;font-size:0.95rem;">🔒 Ubah Password (opsional)</div>
                <div class="form-group">
                    <label class="form-label">Password Saat Ini</label>
                    <input type="password" name="current_password" class="form-input" placeholder="Isi jika ingin ubah password">
                </div>
                <div class="form-group">
                    <label class="form-label">Password Baru</label>
                    <input type="password" name="password" class="form-input" placeholder="Minimal 6 karakter">
                </div>
                <div class="form-group">
                    <label class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="form-input" placeholder="Ulangi password baru">
                </div>
            </div>

            <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
        </form>
    </div>
</div>
@endsection
