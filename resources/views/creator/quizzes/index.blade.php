@extends('layouts.dashboard')
@section('title', 'Kuis Saya')
@section('page-title', 'Kuis Saya')
@section('breadcrumb', 'KuisYuk! / Kuis Saya')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
    <h2 style="font-weight:900;color:#1f2937;font-size:1.2rem;">📋 Semua Kuis Saya</h2>
    <a href="{{ route('creator.quizzes.create') }}" class="btn btn-primary">➕ Buat Kuis Baru</a>
</div>

@if($quizzes->isEmpty())
<div class="card" style="text-align:center;padding:3rem;">
    <div style="font-size:3.5rem;margin-bottom:1rem;">📭</div>
    <div style="font-weight:800;color:#1f2937;font-size:1.1rem;margin-bottom:0.5rem;">Belum ada kuis</div>
    <div style="color:#6b7280;font-weight:600;margin-bottom:1.25rem;">Yuk buat kuis pertamamu dan bagikan ke peserta!</div>
    <a href="{{ route('creator.quizzes.create') }}" class="btn btn-primary">🚀 Buat Kuis Pertama</a>
</div>
@else
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1.25rem;">
    @foreach($quizzes as $quiz)
    <div class="card" style="transition:all 0.2s;" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 10px 30px rgba(220,38,38,0.12)';" onmouseout="this.style.transform='';this.style.boxShadow='';">
        <!-- Header -->
        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:10px;margin-bottom:1rem;">
            <div style="display:flex;align-items:center;gap:10px;flex:1;min-width:0;">
                <div style="width:46px;height:46px;border-radius:14px;background:linear-gradient(135deg,#DC2626,#b91c1c);display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0;">🎯</div>
                <div style="flex:1;min-width:0;">
                    <div style="font-weight:800;color:#1f2937;font-size:0.95rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $quiz->title }}</div>
                    <div style="font-size:0.78rem;color:#9ca3af;font-weight:600;">{{ $quiz->created_at->format('d M Y') }}</div>
                </div>
            </div>
            <span class="badge {{ $quiz->is_active ? 'badge-green' : 'badge-red' }}" style="flex-shrink:0;">
                {{ $quiz->is_active ? '✅ Aktif' : '⏸️ Off' }}
            </span>
        </div>

        <!-- Stats row -->
        <div style="display:flex;gap:12px;margin-bottom:1rem;background:#fff5f5;border-radius:12px;padding:10px 12px;">
            <div style="text-align:center;flex:1;">
                <div style="font-weight:900;font-size:1.2rem;color:#DC2626;">{{ $quiz->responses_count }}</div>
                <div style="font-size:0.73rem;color:#9ca3af;font-weight:700;">Jawaban</div>
            </div>
            <div style="width:1px;background:#fee2e2;"></div>
            <div style="text-align:center;flex:1;">
                <div style="font-weight:900;font-size:1.2rem;color:#1f2937;">{{ $quiz->questions_count ?? $quiz->questions()->count() }}</div>
                <div style="font-size:0.73rem;color:#9ca3af;font-weight:700;">Soal</div>
            </div>
            <div style="width:1px;background:#fee2e2;"></div>
            <div style="flex:1;overflow:hidden;">
                <div style="font-weight:800;font-size:0.78rem;color:#DC2626;font-family:monospace;">{{ $quiz->slug }}</div>
                <div style="font-size:0.73rem;color:#9ca3af;font-weight:700;">Kode Link</div>
            </div>
        </div>

        <!-- Quiz link -->
        <div style="background:#f9fafb;border-radius:10px;padding:8px 12px;margin-bottom:1rem;display:flex;align-items:center;gap:8px;border:1.5px solid #fee2e2;">
            <span style="font-size:0.78rem;color:#6b7280;font-weight:600;flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ url('/kuis/'.$quiz->slug) }}</span>
            <button onclick="copyLink('{{ url('/kuis/'.$quiz->slug) }}')" style="background:#DC2626;color:#fff;border:none;border-radius:8px;padding:5px 10px;font-size:0.75rem;font-weight:700;cursor:pointer;flex-shrink:0;font-family:'Nunito',sans-serif;">Salin</button>
        </div>

        <!-- Actions -->
        <div style="display:flex;gap:6px;flex-wrap:wrap;">
            <a href="{{ route('creator.quizzes.questions', $quiz->id) }}" class="btn btn-primary btn-sm">✏️ Edit Soal</a>
            <a href="{{ route('creator.quizzes.results', $quiz->id) }}" class="btn btn-info btn-sm">📊 Hasil</a>
            <a href="{{ route('creator.quizzes.export', $quiz->id) }}" class="btn btn-success btn-sm">📥 Excel</a>
            <form action="{{ route('creator.quizzes.toggle', $quiz->id) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-warning btn-sm">{{ $quiz->is_active ? '⏸️' : '▶️' }}</button>
            </form>
            <form action="{{ route('creator.quizzes.destroy', $quiz->id) }}" method="POST" style="display:inline;" onsubmit="return confirmDelete(event, this)">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm">🗑️</button>
            </form>
        </div>
    </div>
    @endforeach
</div>

<!-- Pagination -->
<div class="pagination">
    {{ $quizzes->links() }}
</div>
@endif

@push('scripts')
<script>
function copyLink(url) {
    navigator.clipboard.writeText(url).then(() => {
        Swal.fire({ icon:'success', title:'Link Disalin!', text:'Link kuis berhasil disalin ke clipboard.', timer:2000, showConfirmButton:false });
    });
}
function confirmDelete(e, form) {
    e.preventDefault();
    Swal.fire({
        title: 'Hapus Kuis?',
        text: 'Semua soal dan jawaban akan ikut terhapus. Tidak bisa dikembalikan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#DC2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '🗑️ Ya, Hapus',
        cancelButtonText: 'Batal'
    }).then(r => { if(r.isConfirmed) form.submit(); });
}
</script>
@endpush
@endsection
