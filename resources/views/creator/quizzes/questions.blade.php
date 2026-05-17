@extends('layouts.dashboard')
@section('title', 'Kelola Soal — '.$quiz->title)
@section('page-title', 'Kelola Soal')
@section('breadcrumb', 'KuisYuk! / Kuis / Soal')

@push('head')
<style>
.question-card { background:#fff; border-radius:16px; padding:1.25rem 1.5rem; border:1.5px solid #fee2e2; margin-bottom:1rem; }
.question-number { width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#DC2626,#b91c1c);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:900;font-size:0.9rem;flex-shrink:0; }
.option-input-row { display:flex; gap:8px; align-items:center; margin-bottom:8px; }
.option-input-row input[type="text"] { flex:1; }
.option-input-row .radio-correct { display:flex; align-items:center; gap:5px; padding:0 8px; }
</style>
@endpush

@section('content')
<div style="display:grid;grid-template-columns:1fr 360px;gap:1.5rem;align-items:start;">

    <!-- Left: Questions List -->
    <div>
        <!-- Quiz Info Header -->
        <div style="background:linear-gradient(135deg,#DC2626,#b91c1c);border-radius:18px;padding:1.25rem 1.5rem;margin-bottom:1.5rem;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
            <div>
                <div style="font-family:'Fredoka One',cursive;font-size:1.2rem;color:#fff;">{{ $quiz->title }}</div>
                <div style="color:rgba(255,255,255,0.8);font-size:0.85rem;font-weight:600;">{{ $questions->count() }} soal · Kode: <strong>{{ $quiz->slug }}</strong></div>
            </div>
            <div style="display:flex;gap:8px;flex-wrap:wrap;">
                <a href="{{ url('/kuis/'.$quiz->slug) }}" target="_blank" class="btn btn-outline-white btn-sm">👁️ Preview</a>
                <button onclick="copyLink('{{ url('/kuis/'.$quiz->slug) }}')" class="btn btn-white btn-sm">🔗 Salin Link</button>
                <a href="{{ route('creator.quizzes.results', $quiz->id) }}" class="btn btn-white btn-sm">📊 Hasil</a>
            </div>
        </div>

        <!-- Questions -->
        <h3 style="font-weight:900;color:#1f2937;margin-bottom:1rem;">📋 Daftar Soal ({{ $questions->count() }})</h3>

        @forelse($questions as $q)
        <div class="question-card">
            <div style="display:flex;gap:12px;align-items:flex-start;">
                <div class="question-number">{{ $q->order }}</div>
                <div style="flex:1;">
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:0.5rem;flex-wrap:wrap;">
                        <span class="badge {{ $q->question_type === 'multiple_choice' ? 'badge-blue' : 'badge-purple' }}">
                            {{ $q->question_type === 'multiple_choice' ? '🔘 Pilihan Ganda' : '📝 Esai' }}
                        </span>
                    </div>
                    <div style="font-weight:700;color:#1f2937;font-size:0.92rem;margin-bottom:0.5rem;">{{ $q->question_text }}</div>

                    @if($q->question_image)
                    <img src="{{ Storage::url($q->question_image) }}" alt="Gambar soal" style="max-height:120px;border-radius:10px;margin-bottom:0.5rem;">
                    @endif

                    @if($q->question_type === 'multiple_choice')
                    <div style="display:flex;flex-direction:column;gap:5px;margin-top:0.5rem;">
                        @foreach($q->options as $opt)
                        <div style="display:flex;align-items:center;gap:8px;padding:6px 10px;border-radius:9px;background:{{ $opt->is_correct ? '#dcfce7' : '#f9fafb' }};border:1.5px solid {{ $opt->is_correct ? '#bbf7d0' : '#f3f4f6' }};">
                            <span style="font-size:0.75rem;">{{ $opt->is_correct ? '✅' : '○' }}</span>
                            <span style="font-size:0.85rem;font-weight:600;color:{{ $opt->is_correct ? '#15803d' : '#374151' }};">{{ $opt->option_text }}</span>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
                <form action="{{ route('creator.questions.destroy', $q->id) }}" method="POST" onsubmit="return confirmDeleteQ(event,this)">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">🗑️</button>
                </form>
            </div>
        </div>
        @empty
        <div class="card" style="text-align:center;padding:2.5rem;">
            <div style="font-size:3rem;margin-bottom:0.75rem;">📭</div>
            <div style="font-weight:800;color:#1f2937;margin-bottom:0.5rem;">Belum ada soal</div>
            <div style="color:#6b7280;font-weight:600;font-size:0.88rem;">Tambahkan soal pertama menggunakan form di sebelah kanan</div>
        </div>
        @endforelse
    </div>

    <!-- Right: Add Question Form (sticky) -->
    <div style="position:sticky;top:80px;">
        <div class="card">
            <div style="font-weight:900;color:#1f2937;margin-bottom:1.25rem;font-size:1rem;">➕ Tambah Soal Baru</div>

            @if($errors->any())
            <div class="alert alert-error" style="font-size:0.82rem;">
                <span>⚠️</span>
                <div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
            </div>
            @endif

            <form action="{{ route('creator.quizzes.questions.store', $quiz->id) }}" method="POST" enctype="multipart/form-data" id="addQuestionForm">
                @csrf

                <div class="form-group">
                    <label class="form-label">🔤 Jenis Soal</label>
                    <div style="display:flex;gap:8px;">
                        <label class="form-radio-label" style="flex:1;justify-content:center;">
                            <input type="radio" name="question_type" value="multiple_choice" checked onchange="toggleType()"> <span>🔘 Pilihan Ganda</span>
                        </label>
                        <label class="form-radio-label" style="flex:1;justify-content:center;">
                            <input type="radio" name="question_type" value="essay" onchange="toggleType()"> <span>📝 Esai</span>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">❓ Pertanyaan <span style="color:#DC2626;">*</span></label>
                    <textarea name="question_text" class="form-textarea" placeholder="Tulis pertanyaan di sini..." rows="3" required></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">🖼️ Gambar Soal (opsional)</label>
                    <input type="file" name="question_image" class="form-input" accept="image/*" style="padding:8px 12px;">
                </div>

                <!-- Multiple choice options -->
                <div id="optionsSection">
                    <div class="form-group">
                        <label class="form-label">📋 Pilihan Jawaban <span style="color:#DC2626;">*</span></label>
                        <div style="font-size:0.78rem;color:#9ca3af;font-weight:600;margin-bottom:8px;">Pilih radio button di sebelah kanan untuk menandai jawaban yang benar</div>

                        <div id="optionsContainer">
                            @foreach(['A','B','C','D'] as $i => $letter)
                            <div class="option-input-row">
                                <span style="width:24px;height:24px;border-radius:50%;background:#fee2e2;color:#DC2626;font-weight:800;font-size:0.8rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;">{{ $letter }}</span>
                                <input type="text" name="options[{{ $i }}]" class="form-input" placeholder="Pilihan {{ $letter }}">
                                <label class="radio-correct" title="Jawaban benar">
                                    <input type="radio" name="is_correct" value="{{ $i }}" {{ $i === 0 ? 'checked' : '' }} style="accent-color:#16a34a;width:16px;height:16px;">
                                    <span style="font-size:0.75rem;color:#16a34a;font-weight:700;">✓</span>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">
                    ➕ Tambah Soal
                </button>
            </form>
        </div>

        <!-- Quiz settings quick edit -->
        <div class="card" style="margin-top:1rem;">
            <div style="font-weight:800;color:#1f2937;margin-bottom:0.75rem;font-size:0.9rem;">⚙️ Pengaturan Cepat</div>
            <form action="{{ route('creator.quizzes.toggle', $quiz->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn {{ $quiz->is_active ? 'btn-warning' : 'btn-success' }}" style="width:100%;justify-content:center;margin-bottom:8px;">
                    {{ $quiz->is_active ? '⏸️ Nonaktifkan Kuis' : '▶️ Aktifkan Kuis' }}
                </button>
            </form>
            <a href="{{ route('creator.quizzes.edit', $quiz->id) }}" class="btn btn-secondary" style="width:100%;justify-content:center;">✏️ Edit Info Kuis</a>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleType() {
    const type = document.querySelector('input[name="question_type"]:checked').value;
    document.getElementById('optionsSection').style.display = type === 'multiple_choice' ? 'block' : 'none';
}

function copyLink(url) {
    navigator.clipboard.writeText(url).then(() => {
        Swal.fire({ icon:'success', title:'Link Disalin!', timer:2000, showConfirmButton:false });
    });
}

function confirmDeleteQ(e, form) {
    e.preventDefault();
    Swal.fire({
        title: 'Hapus Soal?',
        text: 'Soal dan semua jawaban terkait akan dihapus.',
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
