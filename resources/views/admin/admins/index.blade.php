@extends('layouts.dashboard')
@section('title', 'Akun Admin')
@section('page-title', 'Kelola Akun Admin')
@section('breadcrumb', 'KuisYuk! / Admin / Akun Admin')

@section('content')
<div style="display:grid;grid-template-columns:1fr 380px;gap:1.5rem;align-items:start;">

    <!-- List -->
    <div class="card">
        <div style="font-weight:900;color:#1f2937;margin-bottom:1.25rem;font-size:1rem;">👑 Daftar Admin ({{ $admins->count() }})</div>
        @foreach($admins as $admin)
        <div style="display:flex;align-items:center;gap:12px;padding:12px 0;border-bottom:1.5px solid #fee2e2;">
            <div style="width:44px;height:44px;border-radius:50%;background:linear-gradient(135deg,#f59e0b,#d97706);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:1rem;flex-shrink:0;">
                {{ strtoupper(substr($admin->name,0,1)) }}
            </div>
            <div style="flex:1;">
                <div style="font-weight:800;color:#1f2937;">{{ $admin->name }}
                    @if($admin->id === auth()->id()) <span class="badge badge-yellow" style="font-size:0.7rem;">Kamu</span> @endif
                </div>
                <div style="font-size:0.82rem;color:#9ca3af;font-weight:600;">{{ $admin->email }}</div>
            </div>
            @if($admin->id !== auth()->id())
            <form action="{{ route('admin.admins.destroy', $admin->id) }}" method="POST" onsubmit="return confirmDel(event,this)">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm">🗑️ Hapus</button>
            </form>
            @endif
        </div>
        @endforeach
    </div>

    <!-- Add Admin Form -->
    <div class="card" style="position:sticky;top:80px;">
        <div style="font-weight:900;color:#1f2937;margin-bottom:1.25rem;font-size:1rem;">➕ Tambah Admin Baru</div>

        @if($errors->any())
        <div class="alert alert-error">
            <span>⚠️</span>
            <div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
        </div>
        @endif

        <form action="{{ route('admin.admins.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">👤 Nama</label>
                <input type="text" name="name" class="form-input" placeholder="Nama admin" value="{{ old('name') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">📧 Email</label>
                <input type="email" name="email" class="form-input" placeholder="email@admin.com" value="{{ old('email') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">🔒 Password</label>
                <input type="password" name="password" class="form-input" placeholder="Minimal 6 karakter" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">
                👑 Tambah Admin
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
function confirmDel(e, form) {
    e.preventDefault();
    Swal.fire({
        title: 'Hapus Admin?',
        icon: 'warning', showCancelButton: true,
        confirmButtonColor: '#DC2626', cancelButtonColor: '#6b7280',
        confirmButtonText: '🗑️ Hapus', cancelButtonText: 'Batal'
    }).then(r => { if(r.isConfirmed) form.submit(); });
}
</script>
@endpush
@endsection
