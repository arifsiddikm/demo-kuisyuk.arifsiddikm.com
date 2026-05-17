@extends('layouts.app')
@section('title', 'KuisYuk! - Platform Kuis Online Paling Seru')

@push('head')
<style>
/* ===== NAVBAR ===== */
.pub-navbar {
    position:fixed; top:0; left:0; right:0; z-index:100;
    padding:0.9rem 6%; display:flex; align-items:center; justify-content:space-between;
    transition:all 0.35s;
}
.pub-navbar.scrolled { background:rgba(185,28,28,0.97); backdrop-filter:blur(14px); box-shadow:0 4px 24px rgba(220,38,38,0.35); }
.pub-nav-brand { font-family:'Fredoka One',cursive; font-size:1.7rem; color:#fff; display:flex; align-items:center; gap:8px; text-decoration:none; }
.pub-nav-links { display:flex; gap:6px; align-items:center; }
.pub-nav-link { color:rgba(255,255,255,0.88); font-weight:700; font-size:0.88rem; text-decoration:none; padding:7px 16px; border-radius:50px; transition:all 0.2s; }
.pub-nav-link:hover { background:rgba(255,255,255,0.18); color:#fff; }
.nav-cta { background:rgba(255,255,255,0.2); color:#fff; padding:8px 20px; border-radius:50px; font-weight:800; font-size:0.88rem; text-decoration:none; border:1.5px solid rgba(255,255,255,0.4); transition:all 0.2s; }
.nav-cta:hover { background:rgba(255,255,255,0.32); color:#fff; }

/* ===== HERO ===== */
.hero-section {
    min-height:100vh;
    background:linear-gradient(150deg,#991b1b 0%,#DC2626 40%,#ef4444 100%);
    position:relative; overflow:hidden; display:flex; flex-direction:column; padding-top:70px;
}
.hero-mesh { position:absolute; inset:0; pointer-events:none;
    background: radial-gradient(ellipse 700px 420px at 80% 15%, rgba(255,255,255,0.08) 0%, transparent 70%),
                radial-gradient(ellipse 400px 400px at 8% 85%, rgba(0,0,0,0.14) 0%, transparent 70%); }
.hb { position:absolute; border-radius:50%; background:rgba(255,255,255,0.07); pointer-events:none; }
.hero-body { flex:1; display:flex; align-items:center; justify-content:space-between; padding:3rem 6% 2rem; gap:3rem; position:relative; z-index:2; }
.hero-badge { display:inline-flex; align-items:center; gap:8px; background:rgba(255,255,255,0.18); backdrop-filter:blur(10px); padding:6px 18px; border-radius:50px; margin-bottom:1.2rem; border:1px solid rgba(255,255,255,0.3); }
.hero-badge span { color:#fff; font-weight:700; font-size:0.83rem; }
.hero-title { font-family:'Fredoka One',cursive; font-size:clamp(2.8rem,5.5vw,5.2rem); color:#fff; line-height:1.05; margin-bottom:1.1rem; text-shadow:0 4px 24px rgba(0,0,0,0.15); }
.hero-title .hl { background:linear-gradient(135deg,#fde68a,#fbbf24,#f59e0b); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; filter:drop-shadow(0 2px 8px rgba(251,191,36,0.4)); }
.hero-sub { font-size:1.05rem; color:rgba(255,255,255,0.88); line-height:1.78; max-width:500px; margin-bottom:2rem; font-weight:600; }
.hero-actions { display:flex; gap:12px; flex-wrap:wrap; margin-bottom:2rem; }
.hero-trust { display:flex; gap:1.5rem; flex-wrap:wrap; }
.hero-trust-item { color:rgba(255,255,255,0.82); font-size:0.84rem; font-weight:700; display:flex; align-items:center; gap:6px; }

/* Mockup */
.quiz-mockup { background:#fff; border-radius:24px; padding:1.6rem; max-width:360px; width:100%; flex-shrink:0; box-shadow:0 24px 64px rgba(0,0,0,0.28),0 0 0 1px rgba(255,255,255,0.2); border:2px solid rgba(255,255,255,0.3); animation:mockFloat 4s ease-in-out infinite; }
@keyframes mockFloat { 0%,100%{transform:translateY(0) rotate(0deg);} 40%{transform:translateY(-14px) rotate(1deg);} 70%{transform:translateY(-7px) rotate(-0.5deg);} }
.mockup-prog { height:7px; background:#fee2e2; border-radius:10px; margin-bottom:1.2rem; overflow:hidden; }
.mockup-bar { height:100%; border-radius:10px; background:linear-gradient(90deg,#DC2626,#f59e0b); width:40%; transition:width 0.6s ease; }
.opt { width:100%; text-align:left; padding:10px 14px; border:2px solid #fca5a5; border-radius:12px; font-weight:700; color:#374151; background:#fff; cursor:pointer; transition:all 0.2s; margin-bottom:8px; font-family:'Nunito',sans-serif; font-size:0.86rem; display:flex; align-items:center; gap:10px; }
.opt:hover { border-color:#DC2626; background:#fff5f5; transform:scale(1.02); }
.opt.picked { border-color:#DC2626; background:linear-gradient(135deg,#DC2626,#b91c1c); color:#fff; }
.opt-l { width:26px;height:26px;border-radius:50%;background:rgba(220,38,38,0.1);color:#DC2626;font-weight:900;font-size:0.8rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:all 0.2s; }
.opt.picked .opt-l { background:rgba(255,255,255,0.25); color:#fff; }

/* Wave only at bottom of hero */
.hero-wave { position:absolute; bottom:-2px; left:0; right:0; line-height:0; }

/* ===== SECTIONS ===== */
.section { padding:5.5rem 6%; }
.sec-label { display:inline-block;background:#fee2e2;color:#DC2626;padding:5px 16px;border-radius:50px;font-weight:800;font-size:0.77rem;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:0.75rem; }
.sec-title { font-family:'Fredoka One',cursive; font-size:clamp(1.8rem,3.5vw,2.8rem); color:#1f2937; margin-bottom:0.75rem; }
.sec-sub { color:#6b7280; font-size:0.97rem; font-weight:600; max-width:520px; line-height:1.7; }

/* ===== HOW IT WORKS ===== */
.how-grid { display:grid; grid-template-columns:1fr 1fr; gap:4rem; align-items:start; margin-top:3.5rem; }
/* timeline left */
.tl-list { position:relative; }
.tl-list::before { content:''; position:absolute; left:22px; top:0; bottom:0; width:3px; background:linear-gradient(180deg,#DC2626,#fbbf24,#DC2626); border-radius:10px; }
.tl-item { display:flex; gap:1.25rem; align-items:flex-start; margin-bottom:2rem; cursor:pointer; }
.tl-dot { width:46px;height:46px;border-radius:50%;background:#f3f4f6;border:3px solid #fca5a5;display:flex;align-items:center;justify-content:center;font-size:1.15rem;flex-shrink:0;z-index:1;position:relative;transition:all 0.3s; }
.tl-item.active .tl-dot { background:linear-gradient(135deg,#DC2626,#b91c1c); border-color:#DC2626; box-shadow:0 4px 16px rgba(220,38,38,0.4); }
.tl-body { padding-top:6px; }
.tl-title { font-weight:800; color:#6b7280; font-size:0.95rem; transition:color 0.3s; }
.tl-item.active .tl-title { color:#DC2626; }
.tl-desc { font-size:0.86rem; font-weight:600; color:#9ca3af; line-height:1.6; margin-top:3px; transition:color 0.3s; }
.tl-item.active .tl-desc { color:#374151; }

/* animated demo panel right */
.demo-panel { position:sticky; top:100px; }
.demo-card { background:#fff; border-radius:22px; padding:1.75rem; box-shadow:0 10px 40px rgba(0,0,0,0.1); border:2px solid #fee2e2; min-height:320px; }
.demo-screen { display:none; animation:fadeIn 0.4s ease; }
.demo-screen.active { display:block; }
@keyframes fadeIn { from{opacity:0;transform:translateY(16px);}to{opacity:1;transform:translateY(0);} }

/* ===== STATS ===== */
.stats-section { background:linear-gradient(135deg,#DC2626 0%,#b91c1c 50%,#991b1b 100%); padding:4.5rem 6%; position:relative; overflow:hidden; }
.stats-section::before { content:'';position:absolute;top:-100px;right:-100px;width:320px;height:320px;border-radius:50%;background:rgba(255,255,255,0.06);pointer-events:none; }
.stats-section::after { content:'';position:absolute;bottom:-80px;left:-80px;width:240px;height:240px;border-radius:50%;background:rgba(255,255,255,0.05);pointer-events:none; }
.stats-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:1.5rem; max-width:860px; margin:0 auto; position:relative; z-index:1; }
.stat-box { background:rgba(255,255,255,0.12); border:1px solid rgba(255,255,255,0.22); backdrop-filter:blur(10px); border-radius:20px; padding:2rem 1.5rem; text-align:center; transition:transform 0.25s; }
.stat-box:hover { transform:translateY(-5px); background:rgba(255,255,255,0.18); }
.stat-box .ico { font-size:2.2rem; display:block; margin-bottom:0.6rem; }
.stat-box .num { font-family:'Fredoka One',cursive; font-size:3.2rem; color:#fff; line-height:1; }
.stat-box .lbl { color:rgba(255,255,255,0.82); font-weight:700; font-size:0.9rem; margin-top:0.4rem; }

/* ===== FEATURES ===== */
.features-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:1.5rem; margin-top:3rem; max-width:960px; margin-left:auto; margin-right:auto; }
.feat-card { background:#fff; border-radius:20px; padding:1.75rem; border:1.5px solid #fee2e2; box-shadow:0 4px 16px rgba(0,0,0,0.05); text-align:center; transition:all 0.3s; }
.feat-card:hover { transform:translateY(-6px); box-shadow:0 14px 36px rgba(220,38,38,0.14); border-color:#fca5a5; }
.feat-icon { width:64px;height:64px;border-radius:20px;display:flex;align-items:center;justify-content:center;font-size:1.9rem;margin:0 auto 1rem;box-shadow:0 4px 14px rgba(0,0,0,0.1); }
.feat-card h3 { font-weight:800; color:#1f2937; margin-bottom:0.45rem; font-size:0.97rem; }
.feat-card p { color:#6b7280; font-size:0.86rem; font-weight:600; line-height:1.65; }

/* ===== TESTIMONIALS - infinite CSS scroll ===== */
.testi-outer { position:relative; margin-top:3rem; overflow:hidden; }
.testi-fade-l,.testi-fade-r { position:absolute;top:0;bottom:0;width:80px;z-index:5;pointer-events:none; }
.testi-fade-l { left:0; background:linear-gradient(to right,#fff,transparent); }
.testi-fade-r { right:0; background:linear-gradient(to left,#fff,transparent); }
.testi-viewport { overflow:hidden; }
.testi-track-wrap { display:flex; gap:1.5rem; animation:testiScroll 35s linear infinite; width:max-content; cursor:default; }
.testi-track-wrap:hover { animation-play-state:paused; }
@keyframes testiScroll { 0%{transform:translateX(0);} 100%{transform:translateX(-50%);} }
.testi-card { background:#fff; border-radius:20px; padding:1.6rem; border:1.5px solid #fee2e2; box-shadow:0 4px 16px rgba(0,0,0,0.06); width:300px; flex-shrink:0; }
.testi-stars { color:#f59e0b; font-size:0.9rem; margin-bottom:0.65rem; }
.testi-text { color:#374151; font-size:0.88rem; line-height:1.72; font-weight:600; margin-bottom:1rem; font-style:italic; min-height:70px; }
.testi-author { display:flex; align-items:center; gap:10px; }
.testi-ava { width:42px;height:42px;border-radius:50%;background:linear-gradient(135deg,#DC2626,#b91c1c);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:0.95rem;flex-shrink:0; }
.testi-name { font-weight:800; font-size:0.86rem; color:#1f2937; }
.testi-role { font-size:0.75rem; color:#9ca3af; font-weight:600; }
/* Manual arrow controls */
.testi-controls { display:flex; justify-content:center; align-items:center; gap:12px; margin-top:1.25rem; }
.testi-arr { width:42px;height:42px;border-radius:50%;background:#fff;border:2px solid #fca5a5;display:flex;align-items:center;justify-content:center;font-size:1rem;cursor:pointer;box-shadow:0 4px 12px rgba(0,0,0,0.08);transition:all 0.2s;color:#DC2626; }
.testi-arr:hover { background:#DC2626;border-color:#DC2626;color:#fff;transform:scale(1.1); }
.testi-speed-label { font-size:0.82rem;font-weight:700;color:#9ca3af; }

/* ===== CTA ===== */
.cta-section { padding:5.5rem 6%; background:linear-gradient(135deg,#0f172a 0%,#1e293b 50%,#0f172a 100%); position:relative; overflow:hidden; text-align:center; }
.cta-glow { position:absolute; border-radius:50%; pointer-events:none; }
.cta-badge { display:inline-block; background:linear-gradient(135deg,rgba(220,38,38,0.3),rgba(251,191,36,0.2)); border:1px solid rgba(220,38,38,0.5); color:#fbbf24; padding:5px 18px; border-radius:50px; font-weight:800; font-size:0.79rem; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:1.25rem; }
.cta-title { font-family:'Fredoka One',cursive; font-size:clamp(2rem,4vw,3.6rem); color:#fff; margin-bottom:1rem; line-height:1.1; }
.cta-title .hl { background:linear-gradient(135deg,#fde68a,#fbbf24,#f59e0b); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.cta-sub { color:rgba(255,255,255,0.65); font-size:1rem; font-weight:600; margin-bottom:2.5rem; max-width:480px; margin-left:auto; margin-right:auto; line-height:1.7; }
.cta-btn-main {
    display:inline-flex; align-items:center; gap:10px;
    padding:16px 38px; border-radius:50px; font-weight:800; font-size:1.05rem;
    background:linear-gradient(135deg,#DC2626 0%,#ef4444 50%,#f59e0b 100%);
    background-size:200% auto; color:#fff; text-decoration:none; border:none; cursor:pointer;
    box-shadow:0 8px 32px rgba(220,38,38,0.5), 0 0 0 0 rgba(220,38,38,0.4);
    transition:all 0.35s; animation:ctaGlow 2.5s ease-in-out infinite;
}
.cta-btn-main:hover { background-position:right center; transform:translateY(-3px) scale(1.04); box-shadow:0 16px 48px rgba(220,38,38,0.6); }
@keyframes ctaGlow { 0%,100%{box-shadow:0 8px 32px rgba(220,38,38,0.5),0 0 0 0 rgba(220,38,38,0.3);} 50%{box-shadow:0 8px 32px rgba(220,38,38,0.5),0 0 0 18px rgba(220,38,38,0);} }
.cta-btn-ghost { display:inline-flex; align-items:center; gap:8px; padding:15px 30px; border-radius:50px; font-weight:700; font-size:1rem; background:transparent; color:rgba(255,255,255,0.82); text-decoration:none; border:2px solid rgba(255,255,255,0.25); transition:all 0.25s; }
.cta-btn-ghost:hover { background:rgba(255,255,255,0.1); border-color:rgba(255,255,255,0.5); color:#fff; transform:translateY(-2px); }
.cta-perks { display:flex; justify-content:center; gap:2rem; flex-wrap:wrap; margin-top:1.75rem; }
.cta-perk { color:rgba(255,255,255,0.52); font-size:0.84rem; font-weight:700; display:flex; align-items:center; gap:6px; }
.cta-perk .ck { color:#4ade80; }

/* ===== FOOTER ===== */
footer { background:#0f172a; padding:4rem 6% 2rem; }
.footer-grid { display:grid; grid-template-columns:2fr 1fr 1fr 1fr; gap:2.5rem; margin-bottom:3rem; }
.footer-brand { font-family:'Fredoka One',cursive; font-size:1.7rem; color:#fff; display:flex; align-items:center; gap:8px; margin-bottom:0.75rem; }
.footer-desc { font-size:0.88rem; line-height:1.8; max-width:260px; color:rgba(255,255,255,0.58); font-weight:600; }
.footer-col h4 { color:#fff; font-weight:800; font-size:0.9rem; margin-bottom:1rem; }
.footer-link { display:block; color:rgba(255,255,255,0.52); text-decoration:none; font-size:0.86rem; font-weight:600; padding:4px 0; transition:color 0.2s; }
.footer-link:hover { color:#fff; }
.footer-social { display:flex; gap:10px; margin-top:1.25rem; }
.footer-social a { width:38px;height:38px;border-radius:10px;background:rgba(255,255,255,0.08);display:flex;align-items:center;justify-content:center;font-size:1rem;text-decoration:none;transition:all 0.2s;border:1px solid rgba(255,255,255,0.1); }
.footer-social a:hover { background:rgba(220,38,38,0.5); transform:translateY(-2px); }
.footer-bottom { border-top:1px solid rgba(255,255,255,0.1); padding-top:1.5rem; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.75rem; font-size:0.84rem; color:rgba(255,255,255,0.4); }

/* ===== UTILS ===== */
@keyframes fadeUp { from{opacity:0;transform:translateY(30px);}to{opacity:1;transform:translateY(0);} }
.fade-up { opacity:0; }
.fade-up.vis { animation:fadeUp 0.65s ease forwards; }
.text-center { text-align:center; }

@media(max-width:1024px) { .how-grid { grid-template-columns:1fr; } .demo-panel { position:relative; top:auto; } .features-grid { grid-template-columns:repeat(2,1fr); } }
@media(max-width:768px) { .hero-body { flex-direction:column; text-align:center; padding:2rem 5% 1rem; } .hero-sub,.hero-actions,.hero-trust { margin-left:auto; margin-right:auto; justify-content:center; } .quiz-mockup { max-width:340px; } .stats-grid { grid-template-columns:1fr; max-width:320px; margin:0 auto; } .features-grid { grid-template-columns:1fr; } .testi-card { flex:0 0 calc(100% - 0px); } .footer-grid { grid-template-columns:1fr 1fr; } }
@media(max-width:480px) { .footer-grid { grid-template-columns:1fr; } .footer-bottom { flex-direction:column; text-align:center; } }
</style>
@endpush

@section('content')

<!-- NAVBAR -->
<nav class="pub-navbar" id="pubNav">
    <a href="{{ route('home') }}" class="pub-nav-brand">🎯 KuisYuk!</a>
    <div class="pub-nav-links">
        @auth
            <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('creator.dashboard') }}" class="pub-nav-link">Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="pub-nav-link">Masuk</a>
            <a href="{{ route('register') }}" class="nav-cta">Daftar Gratis 🎉</a>
        @endauth
    </div>
</nav>

<!-- HERO -->
<section class="hero-section">
    <div class="hero-mesh"></div>
    <div class="hb" style="width:420px;height:420px;top:-160px;right:-120px;opacity:0.5;"></div>
    <div class="hb" style="width:200px;height:200px;bottom:18%;left:4%;"></div>
    <div class="hb" style="width:100px;height:100px;top:38%;left:30%;opacity:0.4;"></div>
    <div class="hb" style="width:70px;height:70px;top:20%;right:28%;opacity:0.35;"></div>

    <div class="hero-body">
        <div style="flex:1;max-width:580px;">
            <div class="hero-badge"><span>✨</span><span>Platform Kuis Online #1 Indonesia</span></div>
            <h1 class="hero-title">Bikin Kuis,<br><span class="hl">Bagikan Link,</span><br>Lihat Hasilnya!</h1>
            <p class="hero-sub">Buat kuis seru dalam hitungan menit. Pilihan ganda, esai, custom identitas peserta. Bagikan via link pendek & pantau semua jawaban real-time. 🚀</p>
            <div class="hero-actions">
                @auth
                    <a href="{{ route('creator.quizzes.create') }}" class="cta-btn-main" style="animation:none;padding:14px 32px;font-size:1rem;">🎯 Buat Kuis Sekarang</a>
                @else
                    <a href="{{ route('register') }}" class="cta-btn-main" style="animation:none;padding:14px 32px;font-size:1rem;">🚀 Mulai Gratis</a>
                    <a href="{{ route('login') }}" class="cta-btn-ghost" style="padding:13px 26px;font-size:0.95rem;border-color:rgba(255,255,255,0.35);">Masuk</a>
                @endauth
            </div>
            <div class="hero-trust">
                <div class="hero-trust-item">✅ Gratis selamanya</div>
                <div class="hero-trust-item">✅ Tanpa batas soal</div>
                <div class="hero-trust-item">✅ Link ≤7 karakter</div>
            </div>
        </div>

        <div class="quiz-mockup">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:1rem;">
                <div style="width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,#DC2626,#b91c1c);display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0;">🎯</div>
                <div><div style="font-weight:800;color:#1f2937;font-size:0.88rem;">Kuis Sejarah Indonesia</div><div style="font-size:0.74rem;color:#9ca3af;font-weight:600;">Soal 2 dari 5</div></div>
            </div>
            <div class="mockup-prog"><div class="mockup-bar" id="mockBar"></div></div>
            <div style="font-weight:800;color:#1f2937;font-size:0.9rem;margin-bottom:1rem;line-height:1.5;">🤔 Siapa proklamator kemerdekaan Republik Indonesia?</div>
            <button class="opt" onclick="pickOpt(this)"><div class="opt-l">A</div>Soekarno &amp; Hatta</button>
            <button class="opt" onclick="pickOpt(this)"><div class="opt-l">B</div>Soeharto &amp; Adam Malik</button>
            <button class="opt" onclick="pickOpt(this)"><div class="opt-l">C</div>Sudirman &amp; Nasution</button>
            <div style="margin-top:0.5rem;display:flex;justify-content:flex-end;">
                <button style="background:linear-gradient(135deg,#DC2626,#b91c1c);color:#fff;border:none;border-radius:50px;padding:8px 22px;font-weight:800;cursor:pointer;font-family:'Nunito',sans-serif;font-size:0.87rem;">Selanjutnya →</button>
            </div>
        </div>
    </div>

    <!-- Clean wave without double-wave -->
    <div class="hero-wave">
        <svg viewBox="0 0 1440 80" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0,40 C360,80 720,0 1080,50 C1260,72 1380,30 1440,40 L1440,80 L0,80 Z" fill="#fff5f5"/>
        </svg>
    </div>
</section>

<!-- HOW IT WORKS -->
<section class="section" style="background:#fff5f5;">
    <div class="fade-up text-center">
        <div class="sec-label">🎯 Cara Kerja</div>
        <h2 class="sec-title">Mudah & Menyenangkan!</h2>
        <p class="sec-sub" style="margin:0 auto 0;">Dari buat kuis sampai lihat hasil — simpel, cepat, seru.</p>
    </div>

    <div class="how-grid">
        <!-- Timeline left -->
        <div class="tl-list">
            <div class="tl-item active" data-step="0" onclick="setStep(0)">
                <div class="tl-dot">📝</div>
                <div class="tl-body">
                    <div class="tl-title">Buat Kuis dalam Menit</div>
                    <div class="tl-desc">Tulis soal pilihan ganda atau esai, upload gambar, atur batas waktu dan identitas peserta.</div>
                </div>
            </div>
            <div class="tl-item" data-step="1" onclick="setStep(1)">
                <div class="tl-dot">🔗</div>
                <div class="tl-body">
                    <div class="tl-title">Bagikan via Link Pendek</div>
                    <div class="tl-desc">Setiap kuis dapat kode unik ≤7 karakter. Salin & share ke WhatsApp, Instagram, dll.</div>
                </div>
            </div>
            <div class="tl-item" data-step="2" onclick="setStep(2)">
                <div class="tl-dot">🎮</div>
                <div class="tl-body">
                    <div class="tl-title">Peserta Ngisi Kuis Seru</div>
                    <div class="tl-desc">Soal muncul 1 per 1. Pilih jawaban → otomatis lanjut! Jawaban auto-save di browser.</div>
                </div>
            </div>
            <div class="tl-item" data-step="3" onclick="setStep(3)">
                <div class="tl-dot">📊</div>
                <div class="tl-body">
                    <div class="tl-title">Pantau & Export Hasil</div>
                    <div class="tl-desc">Semua jawaban masuk ke dashboard real-time. Export ke CSV/Excel satu klik.</div>
                </div>
            </div>
        </div>

        <!-- Animated demo right -->
        <div class="demo-panel fade-up">
            <div class="demo-card">
                <!-- Step 0: Create quiz -->
                <div class="demo-screen active" id="demo-0">
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:1.25rem;padding-bottom:1rem;border-bottom:1.5px solid #fee2e2;">
                        <div style="width:40px;height:40px;background:linear-gradient(135deg,#DC2626,#b91c1c);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0;">📝</div>
                        <div><div style="font-weight:800;color:#1f2937;font-size:0.9rem;">Buat Kuis Baru</div><div style="font-size:0.75rem;color:#9ca3af;font-weight:600;">Isi info dasar kuis kamu</div></div>
                    </div>
                    <div style="margin-bottom:0.75rem;"><div style="font-size:0.8rem;font-weight:700;color:#374151;margin-bottom:4px;">📝 Judul Kuis</div><div style="border:2px solid #fca5a5;border-radius:10px;padding:9px 13px;font-size:0.86rem;color:#1f2937;font-weight:600;background:#fff;">Kuis Sejarah Indonesia Kelas 8</div></div>
                    <div style="margin-bottom:0.75rem;"><div style="font-size:0.8rem;font-weight:700;color:#374151;margin-bottom:4px;">🔘 Jenis Soal</div><div style="display:flex;gap:8px;"><span style="background:#fee2e2;color:#DC2626;padding:5px 12px;border-radius:50px;font-size:0.8rem;font-weight:700;border:2px solid #DC2626;">✓ Pilihan Ganda</span><span style="background:#f3f4f6;color:#6b7280;padding:5px 12px;border-radius:50px;font-size:0.8rem;font-weight:700;">Esai</span></div></div>
                    <div><div style="font-size:0.8rem;font-weight:700;color:#374151;margin-bottom:4px;">⏱️ Batas Waktu</div><div style="border:2px solid #fca5a5;border-radius:10px;padding:9px 13px;font-size:0.86rem;color:#1f2937;font-weight:600;background:#fff;">30 menit</div></div>
                    <div style="margin-top:1rem;background:linear-gradient(135deg,#DC2626,#b91c1c);color:#fff;border-radius:12px;padding:10px;text-align:center;font-weight:800;font-size:0.88rem;cursor:pointer;">🚀 Buat Kuis & Tambah Soal →</div>
                </div>
                <!-- Step 1: Share link -->
                <div class="demo-screen" id="demo-1">
                    <div style="text-align:center;margin-bottom:1.25rem;">
                        <div style="font-size:2.5rem;margin-bottom:0.5rem;">🔗</div>
                        <div style="font-weight:800;color:#1f2937;font-size:0.95rem;">Link Kuis Siap Dibagikan!</div>
                        <div style="font-size:0.82rem;color:#6b7280;font-weight:600;margin-top:4px;">Kode unik 7 karakter sudah dibuat otomatis</div>
                    </div>
                    <div style="background:#fff5f5;border:2px dashed #fca5a5;border-radius:14px;padding:1rem;text-align:center;margin-bottom:1rem;">
                        <div style="font-family:monospace;font-size:1rem;color:#DC2626;font-weight:800;">kuisyuk.com/kuis/<span style="background:#DC2626;color:#fff;padding:2px 8px;border-radius:6px;">AB3X9K2</span></div>
                    </div>
                    <div style="display:flex;gap:8px;flex-wrap:wrap;justify-content:center;">
                        <span style="background:#dcfce7;color:#15803d;padding:5px 12px;border-radius:50px;font-size:0.8rem;font-weight:700;">📱 WhatsApp</span>
                        <span style="background:#fee2e2;color:#DC2626;padding:5px 12px;border-radius:50px;font-size:0.8rem;font-weight:700;">📷 Instagram</span>
                        <span style="background:#dbeafe;color:#1d4ed8;padding:5px 12px;border-radius:50px;font-size:0.8rem;font-weight:700;">✉️ Email</span>
                        <span style="background:#f3e8ff;color:#7c3aed;padding:5px 12px;border-radius:50px;font-size:0.8rem;font-weight:700;">🔗 Copy Link</span>
                    </div>
                    <div style="background:#1f2937;color:#4ade80;border-radius:10px;padding:8px 12px;margin-top:1rem;font-size:0.8rem;font-weight:700;text-align:center;">✅ Aktif • Bisa diakses 24/7</div>
                </div>
                <!-- Step 2: Respondent -->
                <div class="demo-screen" id="demo-2">
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:1rem;">
                        <div style="width:8px;height:8px;border-radius:50%;background:#22c55e;box-shadow:0 0 6px #22c55e;"></div>
                        <span style="font-size:0.8rem;font-weight:700;color:#22c55e;">Peserta sedang mengisi kuis...</span>
                    </div>
                    <div style="background:#f9fafb;border-radius:14px;padding:1rem;margin-bottom:0.75rem;">
                        <div style="font-size:0.75rem;font-weight:700;color:#9ca3af;margin-bottom:4px;">SOAL 2 DARI 5</div>
                        <div style="font-weight:800;color:#1f2937;font-size:0.9rem;margin-bottom:0.75rem;">🤔 Kapan Indonesia merdeka?</div>
                        <div style="background:linear-gradient(135deg,#DC2626,#b91c1c);color:#fff;border-radius:10px;padding:8px 12px;font-size:0.86rem;font-weight:700;margin-bottom:6px;">✓ 17 Agustus 1945</div>
                        <div style="border:2px solid #fca5a5;border-radius:10px;padding:7px 12px;font-size:0.86px;font-weight:700;color:#6b7280;font-size:0.86rem;margin-bottom:6px;">1 Juni 1945</div>
                        <div style="border:2px solid #fca5a5;border-radius:10px;padding:7px 12px;font-size:0.86rem;font-weight:700;color:#6b7280;">18 Agustus 1945</div>
                    </div>
                    <div style="display:flex;align-items:center;gap:8px;background:#fff5f5;border-radius:10px;padding:8px 12px;">
                        <span style="font-size:0.8rem;font-weight:700;color:#6b7280;">💾 Auto-saved di browser</span>
                        <span style="margin-left:auto;font-size:0.78rem;font-weight:700;color:#DC2626;">40% selesai</span>
                    </div>
                </div>
                <!-- Step 3: Results -->
                <div class="demo-screen" id="demo-3">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
                        <div style="font-weight:800;color:#1f2937;font-size:0.9rem;">📊 Dashboard Hasil</div>
                        <span style="background:#dcfce7;color:#15803d;padding:3px 10px;border-radius:50px;font-size:0.78rem;font-weight:700;">Live</span>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:0.75rem;">
                        <div style="background:#fff5f5;border-radius:12px;padding:10px;text-align:center;border:1.5px solid #fee2e2;"><div style="font-weight:900;font-size:1.4rem;color:#DC2626;">47</div><div style="font-size:0.75rem;font-weight:700;color:#9ca3af;">Jawaban</div></div>
                        <div style="background:#f0fdf4;border-radius:12px;padding:10px;text-align:center;border:1.5px solid #bbf7d0;"><div style="font-weight:900;font-size:1.4rem;color:#16a34a;">94%</div><div style="font-size:0.75rem;font-weight:700;color:#9ca3af;">Tingkat Selesai</div></div>
                    </div>
                    <div style="background:#f9fafb;border-radius:12px;padding:10px;margin-bottom:0.75rem;">
                        <div style="font-size:0.78rem;font-weight:700;color:#374151;margin-bottom:6px;">Jawaban Terbaru:</div>
                        <div style="display:flex;flex-direction:column;gap:5px;">
                            <div style="display:flex;align-items:center;gap:8px;font-size:0.82rem;"><span style="width:28px;height:28px;border-radius:50%;background:#DC2626;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:0.75rem;flex-shrink:0;">R</span><span style="font-weight:700;">Ririn</span><span style="margin-left:auto;color:#9ca3af;font-size:0.75rem;">2 mnt lalu</span></div>
                            <div style="display:flex;align-items:center;gap:8px;font-size:0.82rem;"><span style="width:28px;height:28px;border-radius:50%;background:#16a34a;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:0.75rem;flex-shrink:0;">A</span><span style="font-weight:700;">Ahmad</span><span style="margin-left:auto;color:#9ca3af;font-size:0.75rem;">5 mnt lalu</span></div>
                        </div>
                    </div>
                    <div style="background:linear-gradient(135deg,#16a34a,#15803d);color:#fff;border-radius:10px;padding:8px 14px;font-weight:800;font-size:0.86rem;text-align:center;cursor:pointer;">📥 Export Excel</div>
                </div>
            </div>
            <!-- Step indicator -->
            <div style="display:flex;gap:8px;justify-content:center;margin-top:1rem;">
                <div class="step-dot active" id="sdot-0" onclick="setStep(0)"></div>
                <div class="step-dot" id="sdot-1" onclick="setStep(1)"></div>
                <div class="step-dot" id="sdot-2" onclick="setStep(2)"></div>
                <div class="step-dot" id="sdot-3" onclick="setStep(3)"></div>
            </div>
        </div>
    </div>
</section>
<style>.step-dot{width:10px;height:10px;border-radius:50%;background:#fca5a5;cursor:pointer;transition:all 0.2s;}.step-dot.active{background:#DC2626;width:26px;border-radius:5px;}</style>

<!-- STATS (moved below How It Works) -->
<section class="stats-section">
    <div style="text-align:center;margin-bottom:2.5rem;position:relative;z-index:1;">
        <div style="font-family:'Fredoka One',cursive;font-size:1.5rem;color:rgba(255,255,255,0.92);">Dipercaya ribuan kreator se-Indonesia 🇮🇩</div>
    </div>
    <div class="stats-grid" id="statsGrid">
        <div class="stat-box"><span class="ico">👤</span><div class="num" data-count="1200">0</div><div class="lbl">Kreator Terdaftar</div></div>
        <div class="stat-box"><span class="ico">📋</span><div class="num" data-count="3400">0</div><div class="lbl">Kuis Dibuat</div></div>
        <div class="stat-box"><span class="ico">✏️</span><div class="num" data-count="45000">0</div><div class="lbl">Jawaban Masuk</div></div>
    </div>
</section>

<!-- FEATURES -->
<section class="section" style="background:#fff5f5;">
    <div class="text-center fade-up">
        <div class="sec-label">⚡ Fitur Unggulan</div>
        <h2 class="sec-title" style="margin:0 auto;">Semua yang Kamu Butuhkan</h2>
        <p class="sec-sub" style="margin:0.5rem auto 0;">Lengkap, gratis, dan mudah dipakai siapa saja.</p>
    </div>
    <div class="features-grid">
        <div class="feat-card fade-up"><div class="feat-icon" style="background:linear-gradient(135deg,#fee2e2,#fecaca);">📋</div><h3>Buat Kuis Mudah</h3><p>Interface intuitif. Tambah soal, atur urutan, set waktu — dalam hitungan menit.</p></div>
        <div class="feat-card fade-up"><div class="feat-icon" style="background:linear-gradient(135deg,#dcfce7,#bbf7d0);">🔗</div><h3>Link Pendek Unik</h3><p>Kode unik ≤7 karakter per kuis. Mudah dibagikan dan mudah diingat peserta.</p></div>
        <div class="feat-card fade-up"><div class="feat-icon" style="background:linear-gradient(135deg,#dbeafe,#bfdbfe);">💾</div><h3>Auto-Save Jawaban</h3><p>Jawaban tersimpan otomatis di browser. Peserta bisa lanjut tanpa kehilangan progress.</p></div>
        <div class="feat-card fade-up"><div class="feat-icon" style="background:linear-gradient(135deg,#f3e8ff,#e9d5ff);">🆔</div><h3>Verifikasi Kustom</h3><p>Set field wajib seperti NIS, NISN, atau KTP. Bisa diatur hanya boleh diisi sekali per ID.</p></div>
        <div class="feat-card fade-up"><div class="feat-icon" style="background:linear-gradient(135deg,#fef9c3,#fde68a);">📊</div><h3>Dashboard Real-time</h3><p>Lihat semua jawaban masuk seketika. Export ke CSV/Excel dengan satu klik.</p></div>
        <div class="feat-card fade-up"><div class="feat-icon" style="background:linear-gradient(135deg,#ffe4e6,#fecdd3);">🔒</div><h3>Kontrol Penuh</h3><p>Aktifkan atau nonaktifkan kuis kapan saja. Kuis nonaktif tidak bisa diakses peserta.</p></div>
    </div>
</section>

<!-- TESTIMONIALS -->
<section class="section" style="background:#fff;padding-bottom:4rem;">
    <div class="text-center fade-up">
        <div class="sec-label">💬 Testimoni</div>
        <h2 class="sec-title">Apa Kata Mereka?</h2>
        <p class="sec-sub" style="margin:0 auto;">Ribuan kreator sudah pakai KuisYuk! — dari guru SD sampai HRD perusahaan.</p>
    </div>

    @php
    $testimonials = [
        ['name'=>'Ririn Wulandari','role'=>'Guru SD Negeri Bandung','ini'=>'R','text'=>'"Mudah banget! Dalam 10 menit kuis sudah jadi. Fitur NISN-nya keren, bisa tau siapa aja yang udah ngisi."'],
        ['name'=>'Ahmad Fauzi','role'=>'Pemilik UMKM Jakarta','ini'=>'A','text'=>'"Saya pakai untuk survei pelanggan. Link pendek memudahkan share via WA. Hasilnya langsung ke Excel!"'],
        ['name'=>'Dewi Putri','role'=>'Panitia Lomba Online','ini'=>'D','text'=>'"Desainnya lucu dan beda. Peserta lomba saya bilang pengalaman ngisinya menyenangkan. Highly recommended!"'],
        ['name'=>'Budi Santoso','role'=>'Dosen Universitas Brawijaya','ini'=>'B','text'=>'"Untuk pre-test mahasiswa ini sangat membantu. Bisa set batas waktu dan field NIM. Luar biasa praktis!"'],
        ['name'=>'Lina Marlina','role'=>'HRD PT Maju Bersama','ini'=>'L','text'=>'"Kami pakai KuisYuk! untuk assessment rekrutmen. Hasilnya bisa diexport dan mudah dianalisis. Mantap!"'],
        ['name'=>'Rizki Pratama','role'=>'Ketua OSIS SMA 5 Surabaya','ini'=>'R','text'=>'"Bikin kuis pengetahuan umum untuk lomba antar kelas. Gampang banget dan hasilnya rapi!"'],
        ['name'=>'Siti Aminah','role'=>'Trainer Pelatihan Online','ini'=>'S','text'=>'"Platform ini jadi andalan saya untuk post-training evaluation. Fitur esai-nya sangat berguna untuk feedback."'],
        ['name'=>'Yoga Andhika','role'=>'Kreator Konten Edukasi','ini'=>'Y','text'=>'"Saya embed link kuis di bio Instagram. Followers suka banget! Tampilan kuis di HP sangat smooth."'],
        ['name'=>'Farida Hanum','role'=>'Koordinator Pelatihan Dinas','ini'=>'F','text'=>'"Sangat membantu untuk ujian sertifikasi online. Peserta bisa ikut dari mana saja, praktis sekali!"'],
    ];
    @endphp

    <div class="testi-outer">
        <div class="testi-fade-l"></div>
        <div class="testi-fade-r"></div>
        <div class="testi-viewport">
            <div class="testi-track-wrap" id="testiTrack">
                {{-- Original set --}}
                @foreach($testimonials as $t)
                <div class="testi-card">
                    <div class="testi-stars">⭐⭐⭐⭐⭐</div>
                    <p class="testi-text">{{ $t['text'] }}</p>
                    <div class="testi-author">
                        <div class="testi-ava">{{ $t['ini'] }}</div>
                        <div><div class="testi-name">{{ $t['name'] }}</div><div class="testi-role">{{ $t['role'] }}</div></div>
                    </div>
                </div>
                @endforeach
                {{-- Duplicate set for seamless infinite loop --}}
                @foreach($testimonials as $t)
                <div class="testi-card">
                    <div class="testi-stars">⭐⭐⭐⭐⭐</div>
                    <p class="testi-text">{{ $t['text'] }}</p>
                    <div class="testi-author">
                        <div class="testi-ava">{{ $t['ini'] }}</div>
                        <div><div class="testi-name">{{ $t['name'] }}</div><div class="testi-role">{{ $t['role'] }}</div></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <!-- Manual controls -->
        <!-- <div class="testi-controls">
            <button class="testi-arr" id="testiSlower" title="Perlambat" onclick="adjustSpeed(1)">⏪</button>
            <span class="testi-speed-label" id="testiSpeedLabel">Kecepatan Normal</span>
            <button class="testi-arr" id="testiFaster" title="Percepat" onclick="adjustSpeed(-1)">⏩</button>
        </div> -->
    </div>
</section>

<!-- CTA -->
<section class="cta-section">
    <div class="cta-glow" style="top:-120px;left:50%;transform:translateX(-50%);width:600px;height:400px;background:radial-gradient(ellipse,rgba(220,38,38,0.25),transparent 70%);"></div>
    <div class="cta-glow" style="bottom:-100px;left:20%;width:300px;height:300px;background:radial-gradient(ellipse,rgba(251,191,36,0.1),transparent 70%);"></div>
    <div class="cta-glow" style="bottom:-100px;right:15%;width:350px;height:350px;background:radial-gradient(ellipse,rgba(220,38,38,0.12),transparent 70%);"></div>
    <div style="position:relative;z-index:2;max-width:640px;margin:0 auto;">
        <div class="cta-badge">✨ Gratis Selamanya • Tanpa Kartu Kredit</div>
        <h2 class="cta-title">Siap Bikin Kuis<br><span class="hl">Pertamamu?</span></h2>
        <p class="cta-sub">Mulai dalam 60 detik. Ribuan kreator sudah merasakan kemudahan KuisYuk! — sekarang giliran kamu!</p>
        <div style="display:flex;gap:14px;justify-content:center;flex-wrap:wrap;align-items:center;">
            @auth
                <a href="{{ route('creator.quizzes.create') }}" class="cta-btn-main">🎯 Buat Kuis Sekarang</a>
            @else
                <a href="{{ route('register') }}" class="cta-btn-main">🚀 Daftar Gratis Sekarang</a>
                <a href="{{ route('login') }}" class="cta-btn-ghost">Sudah punya akun →</a>
            @endauth
        </div>
        <div class="cta-perks">
            <div class="cta-perk"><span class="ck">✓</span> Gratis selamanya</div>
            <div class="cta-perk"><span class="ck">✓</span> Tanpa batas kuis</div>
            <div class="cta-perk"><span class="ck">✓</span> Export kapan saja</div>
            <div class="cta-perk"><span class="ck">✓</span> Bahasa Indonesia</div>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer>
    <div class="footer-grid">
        <div>
            <div class="footer-brand">🎯 KuisYuk!</div>
            <p class="footer-desc">Platform kuis online paling seru dan mudah di Indonesia. Buat kuis, bagikan link, pantau hasil — semua gratis!</p>
            <div class="footer-social">
                <a href="https://www.instagram.com/arifsiddikm/" target="_blank" rel="noopener" title="Instagram @arifsiddikm">📷</a>
                <a href="#" title="Twitter/X">𝕏</a>
                <a href="#" title="YouTube">▶️</a>
                <a href="#" title="TikTok">🎵</a>
            </div>
        </div>
        <div>
            <h4>Platform</h4>
            <a href="{{ route('home') }}" class="footer-link">Beranda</a>
            @guest
            <a href="{{ route('register') }}" class="footer-link">Daftar Gratis</a>
            <a href="{{ route('login') }}" class="footer-link">Masuk</a>
            @endguest
            @auth
            <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('creator.dashboard') }}" class="footer-link">Dashboard</a>
            @endauth
        </div>
        <div>
            <h4>Fitur</h4>
            <a href="#" class="footer-link">Kuis Pilihan Ganda</a>
            <a href="#" class="footer-link">Kuis Esai</a>
            <a href="#" class="footer-link">Link Pendek</a>
            <a href="#" class="footer-link">Export Excel</a>
            <a href="#" class="footer-link">Timer Kuis</a>
        </div>
        <div>
            <h4>Bantuan</h4>
            <a href="#" class="footer-link">Cara Penggunaan</a>
            <a href="#" class="footer-link">FAQ</a>
            <a href="#" class="footer-link">Kontak Kami</a>
            <a href="#" class="footer-link">Kebijakan Privasi</a>
            <a href="#" class="footer-link">Syarat & Ketentuan</a>
        </div>
    </div>
    <div class="footer-bottom">
        <div>© {{ date('Y') }} <strong style="color:#fff;">KuisYuk!</strong> — Dibuat dengan ❤️ untuk Indonesia</div>
        <div>Powered by Laravel 13 + Tailwind CSS</div>
    </div>
</footer>

@push('scripts')
<script>
// Navbar scroll
window.addEventListener('scroll', () => {
    document.getElementById('pubNav').classList.toggle('scrolled', window.scrollY > 40);
});

// Hero mockup
function pickOpt(btn) {
    btn.closest('.quiz-mockup').querySelectorAll('.opt').forEach(b => b.classList.remove('picked'));
    btn.classList.add('picked');
    setTimeout(() => { document.getElementById('mockBar').style.width = '60%'; }, 300);
}

// ── How it works - auto step every 2.5s ──
let currentStep = 0;
let stepTimer   = null;

function setStep(n) {
    document.querySelectorAll('.tl-item').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.demo-screen').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.step-dot').forEach(el => el.classList.remove('active'));
    document.querySelector(`.tl-item[data-step="${n}"]`).classList.add('active');
    document.getElementById('demo-' + n).classList.add('active');
    document.getElementById('sdot-' + n).classList.add('active');
    currentStep = n;
}

function nextStep() { setStep((currentStep + 1) % 4); }

function startStepAuto() {
    clearInterval(stepTimer);
    stepTimer = setInterval(nextStep, 2500);
}

// Pause on hover over demo panel
const demoEl = document.getElementById('demo-0')?.closest('.demo-panel') || document.querySelector('.demo-card');
if (demoEl) {
    demoEl.addEventListener('mouseenter', () => clearInterval(stepTimer));
    demoEl.addEventListener('mouseleave', startStepAuto);
}

// Click resets auto timer
document.querySelectorAll('.tl-item').forEach(el => {
    el.addEventListener('click', () => { clearInterval(stepTimer); startStepAuto(); });
});

startStepAuto();

// ── Scroll fade-up ──
const fadeEls = document.querySelectorAll('.fade-up');
const fadeObs = new IntersectionObserver(entries => {
    entries.forEach((e, i) => {
        if (e.isIntersecting) {
            e.target.style.animationDelay = (i * 0.08) + 's';
            e.target.classList.add('vis');
            fadeObs.unobserve(e.target);
        }
    });
}, { threshold: 0.12 });
fadeEls.forEach(el => fadeObs.observe(el));

// ── Count-up stats ──
function countUp(el, target) {
    let n = 0; const s = target / 80;
    const t = setInterval(() => {
        n = Math.min(n + s, target);
        el.textContent = Math.floor(n).toLocaleString('id') + '+';
        if (n >= target) clearInterval(t);
    }, 20);
}
const statsObs = new IntersectionObserver(entries => {
    if (entries[0].isIntersecting) {
        document.querySelectorAll('[data-count]').forEach(el => countUp(el, +el.dataset.count));
        statsObs.disconnect();
    }
}, { threshold: 0.4 });
const sg = document.getElementById('statsGrid');
if (sg) statsObs.observe(sg);

// ── Testimonial: CSS infinite scroll with speed control ──
const testiTrack = document.getElementById('testiTrack');
let testiDuration = 35; // seconds
const speeds = [20, 28, 35, 45, 60];
const speedLabels = ['Sangat Cepat','Cepat','Normal','Pelan','Sangat Pelan'];
let speedIdx = 2; // start at Normal

function applySpeed() {
    testiDuration = speeds[speedIdx];
    testiTrack.style.animationDuration = testiDuration + 's';
    document.getElementById('testiSpeedLabel').textContent = speedLabels[speedIdx];
}

function adjustSpeed(dir) {
    // dir: 1 = slower, -1 = faster
    speedIdx = Math.max(0, Math.min(speeds.length - 1, speedIdx + dir));
    applySpeed();
}

// Pause on hover
testiTrack.addEventListener('mouseenter', () => { testiTrack.style.animationPlayState = 'paused'; });
testiTrack.addEventListener('mouseleave', () => { testiTrack.style.animationPlayState = 'running'; });

applySpeed();
</script>
@endpush
@endsection
