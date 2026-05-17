@extends('layouts.app')
@section('title', $quiz->title.' — KuisYuk!')

@push('head')
<style>
body { background:#fff5f5; }
.quiz-wrap { min-height:100vh; display:flex; flex-direction:column; }

/* Topbar */
.q-topbar { background:#DC2626; padding:0.85rem 1.5rem; display:flex; align-items:center; justify-content:space-between; box-shadow:0 4px 16px rgba(220,38,38,0.3); position:sticky; top:0; z-index:50; }
.q-brand { font-family:'Fredoka One',cursive; font-size:1.35rem; color:#fff; display:flex; align-items:center; gap:8px; }
.q-prog-wrap { height:6px; background:rgba(255,255,255,0.3); border-radius:10px; flex:1; margin:0 1.25rem; overflow:hidden; }
.q-prog-bar { height:100%; background:#fbbf24; border-radius:10px; transition:width 0.5s ease; }
.q-counter { color:rgba(255,255,255,0.92); font-weight:800; font-size:0.88rem; white-space:nowrap; }

/* Main */
.q-main { flex:1; display:flex; align-items:center; justify-content:center; padding:2rem 1rem; }
.q-card { background:#fff; border-radius:24px; padding:2.5rem; max-width:620px; width:100%; box-shadow:0 10px 40px rgba(0,0,0,0.1); border:2px solid #fee2e2; }

/* Slides */
.slide { display:none; }
.slide.active { display:block; animation:slideIn 0.4s ease; }
@keyframes slideIn { from{opacity:0;transform:translateX(28px);}to{opacity:1;transform:translateX(0);} }

/* Welcome */
.welcome-icon { width:80px;height:80px;border-radius:24px;background:linear-gradient(135deg,#DC2626,#b91c1c);display:flex;align-items:center;justify-content:center;font-size:2.2rem;margin:0 auto 1.25rem;box-shadow:0 8px 24px rgba(220,38,38,0.3); }

/* Options */
.opt-btn { width:100%;text-align:left;padding:13px 18px;border:2.5px solid #fca5a5;border-radius:16px;font-weight:700;color:#374151;background:#fff;cursor:pointer;transition:all 0.2s;margin-bottom:10px;font-family:'Nunito',sans-serif;font-size:0.95rem;display:flex;align-items:center;gap:12px; }
.opt-btn:hover { border-color:#DC2626;background:#fff5f5;transform:scale(1.02); }
.opt-btn.selected { border-color:#DC2626;background:linear-gradient(135deg,#DC2626,#b91c1c);color:#fff;transform:scale(1.02);box-shadow:0 6px 20px rgba(220,38,38,0.3); }
.opt-letter { width:30px;height:30px;border-radius:50%;background:rgba(220,38,38,0.1);color:#DC2626;display:flex;align-items:center;justify-content:center;font-weight:900;font-size:0.85rem;flex-shrink:0;transition:all 0.2s; }
.opt-btn.selected .opt-letter { background:rgba(255,255,255,0.25);color:#fff; }

/* Essay */
.essay-ta { width:100%;padding:14px 16px;border:2.5px solid #fca5a5;border-radius:16px;font-size:0.95rem;font-family:'Nunito',sans-serif;color:#1f2937;resize:vertical;min-height:140px;outline:none;transition:border-color 0.2s,box-shadow 0.2s; }
.essay-ta:focus { border-color:#DC2626;box-shadow:0 0 0 4px rgba(220,38,38,0.1); }

/* Form inputs in quiz */
.q-input { width:100%;padding:12px 16px;border:2.5px solid #fca5a5;border-radius:14px;font-size:0.95rem;font-family:'Nunito',sans-serif;color:#1f2937;background:#fff;outline:none;transition:border-color 0.2s,box-shadow 0.2s; }
.q-input:focus { border-color:#DC2626;box-shadow:0 0 0 4px rgba(220,38,38,0.1); }
.q-input.error { border-color:#DC2626;background:#fff5f5; }

/* Nav buttons */
.q-nav { display:flex;align-items:center;justify-content:space-between;margin-top:1.5rem;gap:10px; }

/* Completion */
@keyframes bounce { 0%,100%{transform:translateY(0);}50%{transform:translateY(-16px);} }

/* Timer */
.timer-badge { background:rgba(255,255,255,0.2);color:#fff;padding:5px 14px;border-radius:50px;font-weight:800;font-size:0.88rem; }
.timer-badge.warn { background:rgba(251,191,36,0.3);color:#fbbf24; }
.timer-badge.danger { background:rgba(255,100,100,0.3);color:#ff8080;animation:pulse 1s infinite; }
@keyframes pulse { 0%,100%{opacity:1;}50%{opacity:0.6;} }

/* Error msg */
.field-err { background:#fee2e2;color:#b91c1c;border:1.5px solid #fecaca;border-radius:10px;padding:9px 14px;font-size:0.87rem;font-weight:700;margin-top:8px;display:none;align-items:center;gap:8px; }
.field-err.show { display:flex; }
</style>
@endpush

@section('content')
<div class="quiz-wrap">
    <!-- Topbar -->
    <div class="q-topbar">
        <div class="q-brand">🎯 KuisYuk!</div>
        <div class="q-prog-wrap">
            <div class="q-prog-bar" id="progBar" style="width:0%;"></div>
        </div>
        <div style="display:flex;align-items:center;gap:10px;">
            @if($quiz->time_limit)
            <div class="timer-badge" id="timerBadge">⏱️ <span id="timerDisp">{{ $quiz->time_limit }}:00</span></div>
            @endif
            <div class="q-counter" id="qCounter">0 / {{ $quiz->questions->count() }}</div>
        </div>
    </div>

    <div class="q-main">
        <div class="q-card">

            {{-- ====== SLIDE: WELCOME ====== --}}
            <div class="slide active" id="slide-welcome">
                @if($quiz->cover_image)
                <img src="{{ Storage::url($quiz->cover_image) }}" style="width:100%;border-radius:16px;margin-bottom:1.25rem;max-height:200px;object-fit:cover;">
                @else
                <div class="welcome-icon">🎯</div>
                @endif
                <div style="font-family:'Fredoka One',cursive;font-size:1.8rem;color:#1f2937;text-align:center;margin-bottom:0.5rem;">{{ $quiz->title }}</div>
                @if($quiz->description)
                <div style="color:#6b7280;font-weight:600;text-align:center;line-height:1.7;margin-bottom:1.25rem;font-size:0.95rem;">{!! $quiz->description !!}</div>
                @endif

                <div style="background:#fff5f5;border-radius:14px;padding:1rem;border:1.5px solid #fee2e2;margin-bottom:1.5rem;display:flex;gap:1.5rem;justify-content:center;flex-wrap:wrap;">
                    <div style="text-align:center;">
                        <div style="font-weight:900;font-size:1.5rem;color:#DC2626;">{{ $quiz->questions->count() }}</div>
                        <div style="font-size:0.78rem;color:#9ca3af;font-weight:700;">Soal</div>
                    </div>
                    @if($quiz->time_limit)
                    <div style="text-align:center;">
                        <div style="font-weight:900;font-size:1.5rem;color:#DC2626;">{{ $quiz->time_limit }}</div>
                        <div style="font-size:0.78rem;color:#9ca3af;font-weight:700;">Menit</div>
                    </div>
                    @endif
                    <div style="text-align:center;">
                        <div style="font-weight:900;font-size:1.5rem;color:#DC2626;">{{ $quiz->responses()->count() }}</div>
                        <div style="font-size:0.78rem;color:#9ca3af;font-weight:700;">Peserta</div>
                    </div>
                </div>

                <button onclick="goToIdentity()" class="btn btn-primary btn-lg" style="width:100%;justify-content:center;">
                    🚀 Buka Kuis
                </button>
            </div>

            {{-- ====== SLIDE: IDENTITY ====== --}}
            <div class="slide" id="slide-identity">
                <div style="text-align:center;margin-bottom:1.5rem;">
                    <div style="font-size:2.5rem;margin-bottom:0.5rem;">👋</div>
                    <div style="font-family:'Fredoka One',cursive;font-size:1.4rem;color:#1f2937;margin-bottom:0.25rem;">Siapa Kamu?</div>
                    <div style="color:#9ca3af;font-size:0.88rem;font-weight:600;">Isi data diri dulu sebelum mulai kuis</div>
                </div>

                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-weight:700;color:#374151;margin-bottom:0.4rem;font-size:0.9rem;">👤 Nama Lengkap <span style="color:#DC2626;">*</span></label>
                    <input type="text" id="inputName" class="q-input" placeholder="Masukkan nama kamu...">
                    <div class="field-err" id="errName">⚠️ <span>Nama tidak boleh kosong.</span></div>
                </div>

                @if($quiz->primary_key_enabled)
                <div style="margin-bottom:1rem;">
                    <label style="display:block;font-weight:700;color:#374151;margin-bottom:0.4rem;font-size:0.9rem;">🆔 {{ $quiz->primary_key_label }} <span style="color:#DC2626;">*</span></label>
                    <input type="text" id="inputPK" class="q-input" placeholder="Masukkan {{ $quiz->primary_key_label }} kamu...">
                    <div class="field-err" id="errPK">⚠️ <span id="errPKMsg">{{ $quiz->primary_key_label }} tidak boleh kosong.</span></div>
                </div>
                @if($quiz->primary_key_unique)
                <div style="background:#fff5f5;border-radius:10px;padding:8px 12px;border:1.5px solid #fee2e2;margin-bottom:1rem;font-size:0.82rem;font-weight:700;color:#9ca3af;display:flex;align-items:center;gap:6px;">
                    <span>ℹ️</span> Setiap {{ $quiz->primary_key_label }} hanya bisa mengisi kuis ini sekali.
                </div>
                @endif
                @endif

                <button onclick="startQuiz()" id="btnStart" class="btn btn-primary btn-lg" style="width:100%;justify-content:center;margin-top:0.5rem;">
                    Mulai Kuis! 🎯
                </button>
                <button onclick="showSlide('slide-welcome')" class="btn btn-secondary" style="width:100%;justify-content:center;margin-top:8px;">
                    ← Kembali
                </button>
            </div>

            {{-- ====== SLIDES: QUESTIONS ====== --}}
            @foreach($quiz->questions as $idx => $question)
            <div class="slide" id="slide-q-{{ $idx }}">
                <div style="font-size:0.82rem;color:#DC2626;font-weight:800;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:0.5rem;">Soal {{ $idx + 1 }} dari {{ $quiz->questions->count() }}</div>
                <div style="font-weight:900;color:#1f2937;font-size:1.08rem;line-height:1.5;margin-bottom:1.25rem;">{{ $question->question_text }}</div>

                @if($question->question_image)
                <img src="{{ Storage::url($question->question_image) }}" style="width:100%;border-radius:14px;margin-bottom:1.25rem;max-height:240px;object-fit:cover;">
                @endif

                @if($question->question_type === 'multiple_choice')
                    @foreach($question->options as $oIdx => $option)
                    <button class="opt-btn" onclick="selectOpt({{ $idx }}, {{ $option->id }}, this)">
                        <div class="opt-letter">{{ chr(65+$oIdx) }}</div>
                        <span>{{ $option->option_text }}</span>
                    </button>
                    @endforeach
                @else
                <textarea class="essay-ta" id="essay-{{ $idx }}" placeholder="Tulis jawabanmu di sini..." oninput="saveEssay({{ $idx }}, this.value)"></textarea>
                @endif

                <div class="q-nav">
                    <button onclick="prevQ({{ $idx }})" class="btn btn-secondary" {{ $idx === 0 ? 'style=visibility:hidden;' : '' }}>
                        ← Sebelumnya
                    </button>
                    @if($idx < $quiz->questions->count() - 1)
                        @if($question->question_type === 'essay')
                        <button onclick="nextQ({{ $idx }})" class="btn btn-primary">Selanjutnya →</button>
                        @else
                        <span style="color:#9ca3af;font-size:0.82rem;font-weight:600;text-align:right;">Pilih jawaban untuk<br>lanjut otomatis</span>
                        @endif
                    @else
                    <button onclick="goToSubmit()" class="btn btn-success">✅ Selesai & Kirim</button>
                    @endif
                </div>
            </div>
            @endforeach

            {{-- ====== SLIDE: SUBMIT CONFIRM ====== --}}
            <div class="slide" id="slide-submit">
                <div style="text-align:center;margin-bottom:1.5rem;">
                    <div style="font-size:3rem;margin-bottom:0.75rem;">📝</div>
                    <div style="font-family:'Fredoka One',cursive;font-size:1.5rem;color:#1f2937;margin-bottom:0.5rem;">Kirim Jawaban?</div>
                    <div style="color:#6b7280;font-weight:600;font-size:0.92rem;">Pastikan semua jawaban sudah benar sebelum dikirim.</div>
                </div>

                <div style="background:#fff5f5;border-radius:14px;padding:1rem;border:1.5px solid #fee2e2;margin-bottom:1.5rem;" id="submitSummary"></div>

                <div style="display:flex;gap:10px;">
                    <button onclick="showSlide('slide-q-{{ $quiz->questions->count() - 1 }}')" class="btn btn-secondary" style="flex:1;justify-content:center;">← Cek Ulang</button>
                    <button onclick="submitQuiz()" class="btn btn-success" style="flex:1;justify-content:center;" id="submitBtn">
                        🚀 Kirim Sekarang
                    </button>
                </div>
            </div>

            {{-- ====== SLIDE: DONE ====== --}}
            <div class="slide" id="slide-done">
                <div style="text-align:center;">
                    <div style="font-size:4rem;margin-bottom:0.75rem;animation:bounce 1s ease-in-out infinite;">🎉</div>
                    <div style="font-family:'Fredoka One',cursive;font-size:2rem;color:#1f2937;margin-bottom:0.5rem;">Jawaban Terkirim!</div>
                    <div style="color:#6b7280;font-weight:600;line-height:1.7;margin-bottom:1.75rem;font-size:0.95rem;">
                        Terima kasih, <strong id="doneRespondentName"></strong>!<br>
                        Jawaban kamu untuk kuis <strong>{{ $quiz->title }}</strong> berhasil dikirim. 🎊
                    </div>

                    <div style="background:#dcfce7;border-radius:14px;padding:1rem 1.25rem;margin-bottom:1.5rem;border:1.5px solid #bbf7d0;display:flex;align-items:center;gap:10px;justify-content:center;">
                        <span style="font-size:1.5rem;">✅</span>
                        <div style="text-align:left;">
                            <div style="font-weight:800;color:#15803d;font-size:0.92rem;">Jawaban berhasil disimpan</div>
                            <div style="font-size:0.8rem;color:#16a34a;font-weight:600;">{{ now()->format('d M Y, H:i') }} WIB</div>
                        </div>
                    </div>

                    <div style="display:flex;flex-direction:column;gap:10px;">
                        <a href="{{ route('home') }}" class="btn btn-primary btn-lg" style="justify-content:center;">🏠 Kembali ke Beranda</a>
                        <button onclick="restartQuiz()" class="btn btn-secondary" style="justify-content:center;">🔄 Isi Kuis Lagi</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const SLUG       = '{{ $quiz->slug }}';
const TOTAL_Q    = {{ $quiz->questions->count() }};
const PK_ENABLED = {{ $quiz->primary_key_enabled ? 'true' : 'false' }};
const PK_UNIQUE  = {{ $quiz->primary_key_unique ? 'true' : 'false' }};
const TIME_LIMIT = {{ $quiz->time_limit ?? 'null' }};
const CSRF       = '{{ csrf_token() }}';
const Q_IDS      = { @foreach($quiz->questions as $idx => $q){{ $idx }}:{{ $q->id }},@endforeach };

let answers     = {};
let respName    = '';
let pkValue     = '';
let timerInt    = null;
let timeLeft    = TIME_LIMIT ? TIME_LIMIT * 60 : 0;

// ── LocalStorage helpers ──
function lsKey() { return 'kuisyuk_' + SLUG; }
function lsSave() { localStorage.setItem(lsKey(), JSON.stringify({ answers })); }
function lsLoad() {
    try {
        const d = JSON.parse(localStorage.getItem(lsKey()) || '{}');
        answers = d.answers || {};
    } catch(e) { answers = {}; }
}
function lsClear() { localStorage.removeItem(lsKey()); }

// ── Slide system ──
function showSlide(id) {
    document.querySelectorAll('.slide').forEach(s => s.classList.remove('active'));
    document.getElementById(id).classList.add('active');
    updateProgress();
}

function updateProgress() {
    const n = Object.keys(answers).length;
    document.getElementById('progBar').style.width = TOTAL_Q > 0 ? (n / TOTAL_Q * 100) + '%' : '0%';
    document.getElementById('qCounter').textContent = n + ' / ' + TOTAL_Q;
}

function goToIdentity() { showSlide('slide-identity'); }

// ── Identity validation ──
async function startQuiz() {
    respName = document.getElementById('inputName')?.value.trim() || '';

    // Validate name
    if (!respName) {
        showErr('errName', 'Nama tidak boleh kosong.');
        document.getElementById('inputName').classList.add('error');
        return;
    }
    hideErr('errName');
    document.getElementById('inputName')?.classList.remove('error');

    if (PK_ENABLED) {
        pkValue = document.getElementById('inputPK')?.value.trim() || '';
        if (!pkValue) {
            showErr('errPK', document.getElementById('errPKMsg').textContent);
            document.getElementById('inputPK').classList.add('error');
            return;
        }

        if (PK_UNIQUE) {
            // Validate with server BEFORE starting quiz
            const btn = document.getElementById('btnStart');
            btn.disabled = true;
            btn.textContent = '⏳ Memeriksa...';

            try {
                const res = await fetch(`/kuis/${SLUG}/validate-identity`, {
                    method: 'POST',
                    headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': CSRF },
                    body: JSON.stringify({ primary_key_value: pkValue })
                });
                const data = await res.json();

                if (!data.valid) {
                    showErr('errPK', data.error);
                    document.getElementById('inputPK').classList.add('error');
                    btn.disabled = false;
                    btn.textContent = 'Mulai Kuis! 🎯';
                    // Show SweetAlert
                    Swal.fire({
                        icon: 'error',
                        title: 'Tidak Bisa Lanjut',
                        text: data.error,
                        confirmButtonColor: '#DC2626'
                    });
                    return;
                }
            } catch(e) {
                btn.disabled = false;
                btn.textContent = 'Mulai Kuis! 🎯';
                // If server unreachable, still allow continue (will be checked on submit)
            }

            btn.disabled = false;
            btn.textContent = 'Mulai Kuis! 🎯';
        }

        hideErr('errPK');
        document.getElementById('inputPK')?.classList.remove('error');
    }

    lsLoad();
    showSlide('slide-q-0');
    if (TIME_LIMIT) startTimer();
}

function showErr(id, msg) {
    const el = document.getElementById(id);
    if (el) {
        el.querySelector('span') && (el.querySelector('span').textContent = msg);
        el.classList.add('show');
    }
}
function hideErr(id) {
    const el = document.getElementById(id);
    if (el) el.classList.remove('show');
}

// ── Timer ──
function startTimer() {
    timerInt = setInterval(() => {
        timeLeft--;
        const m = Math.floor(timeLeft / 60), s = timeLeft % 60;
        document.getElementById('timerDisp').textContent = m + ':' + String(s).padStart(2,'0');
        const b = document.getElementById('timerBadge');
        if      (timeLeft <= 60)  b.className = 'timer-badge danger';
        else if (timeLeft <= 180) b.className = 'timer-badge warn';
        if (timeLeft <= 0) { clearInterval(timerInt); submitQuiz(true); }
    }, 1000);
}

// ── Options ──
function selectOpt(qIdx, optId, btn) {
    btn.closest('.slide').querySelectorAll('.opt-btn').forEach(b => b.classList.remove('selected'));
    btn.classList.add('selected');
    answers[qIdx] = { type:'mc', optId };
    lsSave(); updateProgress();

    // Auto-next with short delay
    setTimeout(() => {
        const next = qIdx + 1;
        if (next < TOTAL_Q) showSlide('slide-q-' + next);
        else goToSubmit();
    }, 380);
}

function saveEssay(qIdx, val) {
    answers[qIdx] = { type:'essay', essay: val };
    lsSave(); updateProgress();
}

function nextQ(idx) {
    if (idx + 1 < TOTAL_Q) showSlide('slide-q-' + (idx + 1));
    else goToSubmit();
}
function prevQ(idx) {
    if (idx > 0) showSlide('slide-q-' + (idx - 1));
    else showSlide('slide-identity');
}

// ── Submit confirm ──
function goToSubmit() {
    let html = '<div style="font-weight:800;color:#1f2937;margin-bottom:0.75rem;font-size:0.9rem;">Ringkasan Jawaban:</div>';
    for (let i = 0; i < TOTAL_Q; i++) {
        const a = answers[i];
        const st = a ? '✅' : '⬜';
        const txt = a ? (a.type === 'essay' ? (a.essay||'-').substring(0,30)+'…' : 'Terpilih') : 'Belum dijawab';
        html += `<div style="display:flex;align-items:center;gap:8px;padding:5px 0;border-bottom:1px solid #fee2e2;font-size:0.85rem;font-weight:600;color:#374151;">
            <span>${st}</span><span>Soal ${i+1}: ${txt}</span></div>`;
    }
    document.getElementById('submitSummary').innerHTML = html;
    showSlide('slide-submit');
}

// ── Submit ──
async function submitQuiz(auto = false) {
    if (!auto) {
        const r = await Swal.fire({
            title:'Kirim Jawaban?', text:'Jawaban yang sudah dikirim tidak bisa diubah!',
            icon:'question', showCancelButton:true,
            confirmButtonColor:'#16a34a', cancelButtonColor:'#6b7280',
            confirmButtonText:'🚀 Ya, Kirim!', cancelButtonText:'Cek Dulu'
        });
        if (!r.isConfirmed) return;
    }

    const btn = document.getElementById('submitBtn');
    btn.disabled = true; btn.textContent = '⏳ Mengirim...';

    const payload = {
        respondent_name: respName,
        primary_key_value: pkValue,
        answers: {}
    };

    Object.keys(answers).forEach(idx => {
        const a = answers[idx];
        payload.answers[Q_IDS[idx]] = a.type === 'mc' ? a.optId : a.essay;
    });

    try {
        const res = await fetch(`/kuis/${SLUG}/submit`, {
            method:'POST',
            headers:{ 'Content-Type':'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify(payload)
        });
        const data = await res.json();

        if (data.success) {
            if (timerInt) clearInterval(timerInt);
            lsClear();
            document.getElementById('doneRespondentName').textContent = respName;
            showSlide('slide-done');
        } else {
            Swal.fire({ icon:'error', title:'Gagal!', text: data.error || 'Terjadi kesalahan.', confirmButtonColor:'#DC2626' });
            btn.disabled = false; btn.textContent = '🚀 Kirim Sekarang';
        }
    } catch(e) {
        Swal.fire({ icon:'error', title:'Error Koneksi!', text:'Gagal terhubung ke server. Coba lagi.', confirmButtonColor:'#DC2626' });
        btn.disabled = false; btn.textContent = '🚀 Kirim Sekarang';
    }
}

// ── Restart quiz ──
function restartQuiz() {
    Swal.fire({
        title: 'Isi Kuis Lagi?',
        text: 'Kamu akan mulai ulang dari awal.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#DC2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '🔄 Ya, Isi Ulang',
        cancelButtonText: 'Batal'
    }).then(r => {
        if (r.isConfirmed) {
            answers = {}; respName = ''; pkValue = '';
            lsClear();
            if (timerInt) clearInterval(timerInt);
            timeLeft = TIME_LIMIT ? TIME_LIMIT * 60 : 0;
            // Reset form inputs
            const nameInput = document.getElementById('inputName');
            if (nameInput) nameInput.value = '';
            const pkInput = document.getElementById('inputPK');
            if (pkInput) pkInput.value = '';
            // Reset options
            document.querySelectorAll('.opt-btn').forEach(b => b.classList.remove('selected'));
            document.querySelectorAll('textarea[id^="essay-"]').forEach(t => t.value = '');
            // Reset progress
            document.getElementById('progBar').style.width = '0%';
            document.getElementById('qCounter').textContent = '0 / ' + TOTAL_Q;
            // Timer badge reset
            if (TIME_LIMIT) {
                document.getElementById('timerBadge').className = 'timer-badge';
                document.getElementById('timerDisp').textContent = TIME_LIMIT + ':00';
            }
            showSlide('slide-welcome');
        }
    });
}

// Restore selected opts on load
lsLoad();
Object.keys(answers).forEach(idx => {
    if (answers[idx]?.type === 'essay') {
        const el = document.getElementById('essay-' + idx);
        if (el) el.value = answers[idx].essay || '';
    }
});
updateProgress();
</script>
@endpush
