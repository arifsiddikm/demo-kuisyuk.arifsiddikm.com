@extends('layouts.dashboard')
@section('title', 'Kelola Pengguna')
@section('page-title', 'Kelola Pengguna')
@section('breadcrumb', 'KuisYuk! / Admin / Pengguna')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;margin-bottom:1.5rem;">
    <h2 style="font-weight:900;color:#1f2937;font-size:1.1rem;">👥 Daftar Kreator ({{ $users->total() }})</h2>
    <div style="display:flex;gap:8px;">
        <a href="{{ route('admin.users.export') }}" class="btn btn-success btn-sm">📥 Export Excel</a>
    </div>
</div>

<!-- Search -->
<form method="GET" action="{{ route('admin.users') }}" style="margin-bottom:1.25rem;">
    <div style="display:flex;gap:8px;max-width:400px;">
        <input type="text" name="search" class="form-input" placeholder="🔍 Cari nama atau email..." value="{{ request('search') }}">
        <button type="submit" class="btn btn-primary btn-sm" style="white-space:nowrap;">Cari</button>
        @if(request('search'))
        <a href="{{ route('admin.users') }}" class="btn btn-secondary btn-sm">✕</a>
        @endif
    </div>
</form>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Jumlah Kuis</th>
                <th>Status</th>
                <th>Terdaftar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $i => $user)
            <tr>
                <td style="font-weight:700;">{{ ($users->currentPage()-1)*$users->perPage()+$i+1 }}</td>
                <td>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#DC2626,#b91c1c);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:0.85rem;flex-shrink:0;">
                            {{ strtoupper(substr($user->name,0,1)) }}
                        </div>
                        <span style="font-weight:700;">{{ $user->name }}</span>
                    </div>
                </td>
                <td style="color:#6b7280;">{{ $user->email }}</td>
                <td><span class="badge badge-blue">{{ $user->quizzes_count }} kuis</span></td>
                <td>
                    <span class="badge {{ $user->is_active ? 'badge-green' : 'badge-red' }}">
                        {{ $user->is_active ? '✅ Aktif' : '⏸️ Nonaktif' }}
                    </span>
                </td>
                <td style="font-size:0.82rem;color:#9ca3af;">{{ $user->created_at->format('d M Y') }}</td>
                <td>
                    <div style="display:flex;gap:6px;">
                        <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn {{ $user->is_active ? 'btn-warning' : 'btn-success' }} btn-sm">
                                {{ $user->is_active ? '⏸️' : '▶️' }}
                            </button>
                        </form>
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirmDeleteUser(event,this)">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">🗑️</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center;padding:2rem;color:#9ca3af;font-weight:700;">
                    Tidak ada pengguna ditemukan
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="pagination">{{ $users->links() }}</div>

@push('scripts')
<script>
function confirmDeleteUser(e, form) {
    e.preventDefault();
    Swal.fire({
        title: 'Hapus Pengguna?',
        text: 'Semua kuis dan data pengguna ini akan dihapus permanen!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#DC2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '🗑️ Hapus',
        cancelButtonText: 'Batal'
    }).then(r => { if(r.isConfirmed) form.submit(); });
}
</script>
@endpush
@endsection
