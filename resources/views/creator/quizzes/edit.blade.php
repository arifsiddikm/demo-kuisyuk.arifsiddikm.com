@extends('layouts.dashboard')
@section('title', 'Edit Kuis')
@section('page-title', 'Edit Info Kuis')
@section('breadcrumb', 'KuisYuk! / Kuis / Edit')

@section('content')
<div style="max-width:680px;">
    <div class="card">
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:1.75rem;padding-bottom:1.25rem;border-bottom:1.5px solid #fee2e2;">
            <div style="width:48px;height:48px;border-radius:14px;background:linear-gradient(135deg,#f59e0b,#d97706);display:flex;align-items:center;justify-content:center;font-size:1.4rem;">✏️</div>
            <div>
                <div style="font-weight:900;color:#1f2937;font-size:1.1rem;">Edit Kuis</div>
                <div style="color:#9ca3af;font-size:0.85rem;font-weight:600;">Kode: <strong style="color:#DC2626;">{{ $quiz->slug }}</strong></div>
            </div>
        </div>

        @if($errors->any())
        <div class="alert alert-error">
            <span>⚠️</span>
            <div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
        </div>
        @endif

        <form action="{{ route('creator.quizzes.update', $quiz->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label class="form-label">📝 Judul Kuis <span style="color:#DC2626;">*</span></label>
                <input type="text" name="title" class="form-input" value="{{ old('title', $quiz->title) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">📄 Deskripsi</label>
                <textarea name="description" class="form-textarea" id="descEditor" rows="4">{{ old('description', $quiz->description) }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">🖼️ Cover Kuis</label>
                @if($quiz->cover_image)
                <div style="margin-bottom:8px;">
                    <img src="{{ Storage::url($quiz->cover_image) }}" style="max-width:180px;border-radius:12px;border:2px solid #fca5a5;">
                </div>
                @endif
                <input type="file" name="cover_image" class="form-input" accept="image/*" style="padding:10px 12px;">
            </div>

            <div class="form-group">
                <label class="form-label">⏱️ Batas Waktu</label>
                <div style="display:flex;align-items:center;gap:10px;">
                    <input type="number" name="time_limit" class="form-input" value="{{ old('time_limit', $quiz->time_limit) }}" min="1" max="180" style="max-width:140px;">
                    <span style="color:#6b7280;font-weight:600;font-size:0.88rem;">menit</span>
                </div>
            </div>

            <div style="background:#fff5f5;border-radius:16px;padding:1.25rem;border:1.5px solid #fee2e2;margin-bottom:1.25rem;">
                <div style="font-weight:800;color:#1f2937;margin-bottom:0.75rem;">🆔 Identitas Peserta</div>
                <div class="form-group" style="margin-bottom:0.75rem;">
                    <label style="display:flex;align-items:center;gap:10px;">
                        <label class="toggle-switch">
                            <input type="checkbox" name="primary_key_enabled" id="pkEnabled" value="1"
                                {{ old('primary_key_enabled', $quiz->primary_key_enabled) ? 'checked' : '' }}
                                onchange="document.getElementById('pkSection').style.display=this.checked?'block':'none'">
                            <span class="toggle-slider"></span>
                        </label>
                        <span style="font-weight:600;color:#374151;font-size:0.9rem;">Aktifkan field identitas tambahan</span>
                    </label>
                </div>
                <div id="pkSection" style="{{ old('primary_key_enabled', $quiz->primary_key_enabled) ? '' : 'display:none;' }}">
                    <div class="form-group" style="margin-bottom:0.75rem;">
                        <label class="form-label" style="font-size:0.85rem;">Label Field</label>
                        <input type="text" name="primary_key_label" class="form-input" value="{{ old('primary_key_label', $quiz->primary_key_label) }}" placeholder="NIS, NISN, KTP...">
                    </div>
                    <label class="form-check-label">
                        <input type="checkbox" name="primary_key_unique" value="1" {{ old('primary_key_unique', $quiz->primary_key_unique) ? 'checked' : '' }}>
                        <span>Hanya boleh diisi sekali per nilai</span>
                    </label>
                </div>
            </div>

            <div style="display:flex;gap:10px;justify-content:flex-end;">
                <a href="{{ route('creator.quizzes.questions', $quiz->id) }}" class="btn btn-secondary">← Kembali</a>
                <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
ClassicEditor.create(document.querySelector('#descEditor'), {
    toolbar: ['bold','italic','|','bulletedList','numberedList','|','blockQuote'],
}).catch(console.error);
</script>
@endpush
@endsection
