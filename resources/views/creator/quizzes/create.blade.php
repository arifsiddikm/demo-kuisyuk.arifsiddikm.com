@extends('layouts.dashboard')
@section('title', 'Buat Kuis Baru')
@section('page-title', 'Buat Kuis Baru')
@section('breadcrumb', 'KuisYuk! / Kuis / Buat Baru')

@section('content')
<div style="max-width:680px;">
    <div class="card">
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:1.75rem;padding-bottom:1.25rem;border-bottom:1.5px solid #fee2e2;">
            <div style="width:48px;height:48px;border-radius:14px;background:linear-gradient(135deg,#DC2626,#b91c1c);display:flex;align-items:center;justify-content:center;font-size:1.4rem;">🎯</div>
            <div>
                <div style="font-weight:900;color:#1f2937;font-size:1.1rem;">Buat Kuis Baru</div>
                <div style="color:#9ca3af;font-size:0.85rem;font-weight:600;">Isi informasi dasar kuis kamu dulu</div>
            </div>
        </div>

        @if($errors->any())
        <div class="alert alert-error">
            <span>⚠️</span>
            <div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
        </div>
        @endif

        <form action="{{ route('creator.quizzes.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label class="form-label">📝 Judul Kuis <span style="color:#DC2626;">*</span></label>
                <input type="text" name="title" class="form-input" placeholder="contoh: Kuis Sejarah Kelas 8 Semester 1" value="{{ old('title') }}" required maxlength="255">
            </div>

            <div class="form-group">
                <label class="form-label">📄 Deskripsi Kuis</label>
                <textarea name="description" class="form-textarea" id="descEditor" placeholder="Jelaskan tentang kuis ini, petunjuk pengerjaan, dll..." rows="4">{{ old('description') }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">🖼️ Cover Kuis (opsional)</label>
                <input type="file" name="cover_image" class="form-input" accept="image/*" style="padding:10px 12px;" onchange="previewCover(this)">
                <div id="coverPreview" style="display:none;margin-top:10px;">
                    <img id="coverImg" style="max-width:200px;border-radius:12px;border:2px solid #fca5a5;">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">⏱️ Batas Waktu (opsional)</label>
                <div style="display:flex;align-items:center;gap:10px;">
                    <input type="number" name="time_limit" class="form-input" placeholder="Menit" value="{{ old('time_limit') }}" min="1" max="180" style="max-width:140px;">
                    <span style="color:#6b7280;font-weight:600;font-size:0.88rem;">menit (kosongkan jika tidak ada batas)</span>
                </div>
            </div>

            <!-- Primary Key Section -->
            <div style="background:#fff5f5;border-radius:16px;padding:1.25rem;border:1.5px solid #fee2e2;margin-bottom:1.25rem;">
                <div style="font-weight:800;color:#1f2937;margin-bottom:0.75rem;display:flex;align-items:center;gap:8px;">
                    🆔 Pengaturan Identitas Peserta
                </div>

                <div class="form-group" style="margin-bottom:0.75rem;">
                    <label class="form-label" style="font-size:0.85rem;">Field Identitas Tambahan (opsional)</label>
                    <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
                        <label class="toggle-switch">
                            <input type="checkbox" name="primary_key_enabled" id="pkEnabled" value="1" {{ old('primary_key_enabled') ? 'checked' : '' }} onchange="togglePK()">
                            <span class="toggle-slider"></span>
                        </label>
                        <span style="font-weight:600;color:#374151;font-size:0.9rem;">Aktifkan field identitas tambahan</span>
                    </div>
                </div>

                <div id="pkSection" style="{{ old('primary_key_enabled') ? '' : 'display:none;' }}">
                    <div class="form-group" style="margin-bottom:0.75rem;">
                        <label class="form-label" style="font-size:0.85rem;">Label Field</label>
                        <input type="text" name="primary_key_label" class="form-input" placeholder="contoh: NIS, NISN, KTP, Nomor Induk" value="{{ old('primary_key_label', 'NIS') }}">
                        <div style="font-size:0.78rem;color:#9ca3af;margin-top:4px;font-weight:600;">Label ini muncul sebagai pertanyaan sebelum kuis dimulai</div>
                    </div>
                    <div>
                        <label class="form-check-label">
                            <input type="checkbox" name="primary_key_unique" value="1" {{ old('primary_key_unique') ? 'checked' : '' }}>
                            <span>Hanya boleh diisi sekali per nilai field ini (cegah duplikasi)</span>
                        </label>
                    </div>
                </div>
            </div>

            <div style="display:flex;gap:10px;justify-content:flex-end;">
                <a href="{{ route('creator.quizzes.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">🚀 Buat Kuis & Tambah Soal →</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
ClassicEditor.create(document.querySelector('#descEditor'), {
    toolbar: ['bold','italic','|','bulletedList','numberedList','|','blockQuote'],
}).catch(console.error);

function togglePK() {
    const s = document.getElementById('pkSection');
    s.style.display = document.getElementById('pkEnabled').checked ? 'block' : 'none';
}

function previewCover(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('coverImg').src = e.target.result;
            document.getElementById('coverPreview').style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
@endsection
