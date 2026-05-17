@extends('layouts.dashboard')
@section('title', 'Hasil Kuis — '.$quiz->title)
@section('page-title', 'Hasil Kuis')
@section('breadcrumb', 'KuisYuk! / Kuis / Hasil')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;margin-bottom:1.5rem;">
    <div>
        <h2 style="font-weight:900;color:#1f2937;font-size:1.1rem;">📊 Hasil: {{ $quiz->title }}</h2>
        <div style="color:#9ca3af;font-weight:600;font-size:0.85rem;">{{ $responses->total() }} jawaban masuk</div>
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
        <a href="{{ route('creator.quizzes.export', $quiz->id) }}" class="btn btn-success btn-sm">📥 Export Excel</a>
        <a href="{{ route('creator.quizzes.questions', $quiz->id) }}" class="btn btn-secondary btn-sm">← Kembali ke Soal</a>
    </div>
</div>

@if($responses->isEmpty())
<div class="card" style="text-align:center;padding:3rem;">
    <div style="font-size:3rem;margin-bottom:0.75rem;">📭</div>
    <div style="font-weight:800;color:#1f2937;margin-bottom:0.5rem;">Belum ada jawaban</div>
    <div style="color:#6b7280;font-weight:600;font-size:0.88rem;">Bagikan link kuis ke peserta untuk mulai menerima jawaban</div>
    <button onclick="copyLink('{{ url('/kuis/'.$quiz->slug) }}')" class="btn btn-primary btn-sm" style="margin-top:1rem;">🔗 Salin Link Kuis</button>
</div>
@else
<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                @if($quiz->primary_key_enabled)
                <th>{{ $quiz->primary_key_label }}</th>
                @endif
                <th>Waktu Submit</th>
                @foreach($quiz->questions as $q)
                <th style="max-width:140px;">S{{ $q->order }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($responses as $i => $response)
            <tr>
                <td style="font-weight:700;">{{ ($responses->currentPage()-1)*$responses->perPage() + $i + 1 }}</td>
                <td>
                    <div style="font-weight:700;">{{ $response->respondent_name }}</div>
                </td>
                @if($quiz->primary_key_enabled)
                <td><span class="badge badge-blue">{{ $response->primary_key_value ?? '-' }}</span></td>
                @endif
                <td style="font-size:0.82rem;color:#6b7280;">{{ $response->submitted_at?->format('d/m/Y H:i') ?? '-' }}</td>
                @php $answersMap = $response->answers->keyBy('question_id'); @endphp
                @foreach($quiz->questions as $q)
                @php $ans = $answersMap->get($q->id); @endphp
                <td style="max-width:180px;">
                    @if(!$ans)
                        <span style="color:#9ca3af;font-size:0.82rem;">-</span>
                    @elseif($q->question_type === 'essay')
                        <span style="font-size:0.82rem;color:#374151;display:block;max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $ans->essay_answer }}">{{ $ans->essay_answer }}</span>
                    @else
                        <span style="font-size:0.82rem;font-weight:700;color:#1f2937;">{{ $ans->option?->option_text ?? '-' }}</span>
                    @endif
                </td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="pagination">{{ $responses->links() }}</div>
@endif

@push('scripts')
<script>
function copyLink(url) {
    navigator.clipboard.writeText(url).then(() => {
        Swal.fire({ icon:'success', title:'Link Disalin!', timer:2000, showConfirmButton:false });
    });
}
</script>
@endpush
@endsection
