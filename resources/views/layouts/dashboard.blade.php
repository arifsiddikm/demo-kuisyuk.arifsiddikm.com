<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — KuisYuk!</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' rx='20' fill='%23DC2626'/><text y='.9em' font-size='70' x='10'>🎯</text></svg>">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Fredoka+One&display=swap" rel="stylesheet">
    <style>
        * { box-sizing:border-box; }
        body { font-family:'Nunito',sans-serif; background:#fff5f5; margin:0; }
        .font-fredoka { font-family:'Fredoka One',cursive; }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width:260px; min-height:100vh;
            background:linear-gradient(180deg,#DC2626 0%,#991b1b 100%);
            position:fixed; left:0; top:0; bottom:0;
            display:flex; flex-direction:column;
            z-index:50; box-shadow:4px 0 20px rgba(220,38,38,0.25);
            overflow-y:auto; transition:transform 0.3s ease;
        }
        .sidebar-brand { padding:1.5rem 1.25rem 1rem; border-bottom:1px solid rgba(255,255,255,0.15); display:flex; align-items:center; gap:10px; text-decoration:none; }
        .sidebar-brand-text { font-family:'Fredoka One',cursive; font-size:1.6rem; color:#fff; }
        .sidebar-user { padding:1rem 1.25rem; border-bottom:1px solid rgba(255,255,255,0.12); display:flex; align-items:center; gap:10px; }
        .sidebar-avatar { width:42px;height:42px;border-radius:50%;background:rgba(255,255,255,0.2);display:flex;align-items:center;justify-content:center;font-size:1.1rem;font-weight:800;color:#fff;flex-shrink:0; }
        .sidebar-username { color:#fff;font-weight:700;font-size:0.92rem;line-height:1.2; }
        .sidebar-role { color:rgba(255,255,255,0.7);font-size:0.78rem;font-weight:600; }
        .sidebar-nav { padding:1rem 0.75rem; flex:1; }
        .sidebar-section-title { font-size:0.72rem;font-weight:800;color:rgba(255,255,255,0.5);text-transform:uppercase;letter-spacing:0.08em;padding:0.5rem 0.5rem 0.25rem;margin-top:0.5rem; }
        .sidebar-link {
            display:flex; align-items:center; gap:10px;
            padding:10px 14px; border-radius:12px;
            color:rgba(255,255,255,0.85); font-weight:700; font-size:0.9rem;
            text-decoration:none; transition:all 0.2s; margin-bottom:3px;
            background:none; border:none; cursor:pointer; width:100%; text-align:left;
        }
        .sidebar-link:hover { background:rgba(255,255,255,0.15); color:#fff; transform:translateX(4px); }
        .sidebar-link.active { background:rgba(255,255,255,0.2); color:#fff; box-shadow:0 2px 10px rgba(0,0,0,0.15); }
        .sidebar-link .icon { font-size:1.1rem; width:22px; text-align:center; flex-shrink:0; }
        .sidebar-footer { padding:1rem 0.75rem; border-top:1px solid rgba(255,255,255,0.15); }

        /* ===== MAIN ===== */
        .main-content { margin-left:260px; min-height:100vh; display:flex; flex-direction:column; }
        .topbar {
            background:#fff; padding:1rem 1.5rem;
            display:flex; align-items:center; justify-content:space-between;
            box-shadow:0 2px 12px rgba(0,0,0,0.06); border-bottom:1.5px solid #fee2e2;
            position:sticky; top:0; z-index:40;
        }
        .topbar-title { font-weight:800; font-size:1.1rem; color:#1f2937; }
        .topbar-breadcrumb { font-size:0.82rem; color:#9ca3af; font-weight:600; }
        .page-content { padding:1.5rem; flex:1; }
        .sidebar-toggle { display:none; background:#DC2626; border:none; border-radius:10px; padding:8px 12px; cursor:pointer; color:#fff; font-size:1.2rem; }
        @media(max-width:768px) {
            .sidebar { transform:translateX(-100%); }
            .sidebar.open { transform:translateX(0); }
            .main-content { margin-left:0; }
            .sidebar-toggle { display:block; }
        }

        /* ===== BUTTONS ===== */
        .btn { display:inline-flex;align-items:center;justify-content:center;gap:6px;padding:10px 22px;border-radius:50px;font-weight:700;font-size:0.92rem;cursor:pointer;border:none;transition:all 0.2s;text-decoration:none;line-height:1.2; }
        .btn:hover { transform:translateY(-2px);box-shadow:0 6px 18px rgba(0,0,0,0.13); }
        .btn:active { transform:translateY(0); }
        .btn-primary { background:linear-gradient(135deg,#DC2626,#b91c1c);color:#fff;box-shadow:0 4px 12px rgba(220,38,38,0.35); }
        .btn-success { background:linear-gradient(135deg,#16a34a,#15803d);color:#fff;box-shadow:0 4px 12px rgba(22,163,74,0.35); }
        .btn-danger  { background:linear-gradient(135deg,#dc2626,#b91c1c);color:#fff; }
        .btn-warning { background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff; }
        .btn-info    { background:linear-gradient(135deg,#0ea5e9,#0284c7);color:#fff; }
        .btn-secondary { background:#f3f4f6;color:#374151;border:2px solid #e5e7eb; }
        .btn-secondary:hover { background:#e5e7eb;box-shadow:none; }
        .btn-sm { padding:6px 14px;font-size:0.8rem; }
        .btn-lg { padding:14px 32px;font-size:1.05rem; }

        /* ===== FORMS ===== */
        .form-group { margin-bottom:1.2rem; }
        .form-label { display:block;font-weight:700;color:#374151;margin-bottom:0.4rem;font-size:0.9rem; }
        .form-input { width:100%;padding:11px 16px;border:2px solid #fca5a5;border-radius:12px;font-size:0.92rem;font-family:'Nunito',sans-serif;color:#1f2937;background:#fff;transition:border-color 0.2s,box-shadow 0.2s;outline:none; }
        .form-input:focus { border-color:#DC2626;box-shadow:0 0 0 4px rgba(220,38,38,0.1); }
        .form-input::placeholder { color:#9ca3af; }
        .form-textarea { width:100%;padding:11px 16px;border:2px solid #fca5a5;border-radius:12px;font-size:0.92rem;font-family:'Nunito',sans-serif;color:#1f2937;background:#fff;resize:vertical;min-height:100px;transition:border-color 0.2s,box-shadow 0.2s;outline:none; }
        .form-textarea:focus { border-color:#DC2626;box-shadow:0 0 0 4px rgba(220,38,38,0.1); }
        .form-select { width:100%;padding:11px 16px;border:2px solid #fca5a5;border-radius:12px;font-size:0.92rem;font-family:'Nunito',sans-serif;color:#1f2937;background:#fff;outline:none;appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%236b7280' stroke-width='1.5' d='M6 8l4 4 4-4'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 12px center;background-size:20px;padding-right:44px;transition:border-color 0.2s,box-shadow 0.2s; }
        .form-select:focus { border-color:#DC2626;box-shadow:0 0 0 4px rgba(220,38,38,0.1); }
        .form-radio-label,.form-check-label { display:inline-flex;align-items:center;gap:8px;cursor:pointer;font-weight:600;color:#374151;padding:7px 15px;border:2px solid #fca5a5;border-radius:50px;transition:all 0.2s;background:#fff;margin-right:8px;margin-bottom:8px; }
        .form-radio-label:hover,.form-check-label:hover { border-color:#DC2626;background:#fff5f5; }
        .form-radio-label input,.form-check-label input { accent-color:#DC2626;width:15px;height:15px; }
        .toggle-switch { position:relative;display:inline-block;width:52px;height:28px; }
        .toggle-switch input { opacity:0;width:0;height:0; }
        .toggle-slider { position:absolute;cursor:pointer;top:0;left:0;right:0;bottom:0;background:#d1d5db;border-radius:28px;transition:0.3s; }
        .toggle-slider::before { position:absolute;content:'';height:20px;width:20px;left:4px;bottom:4px;background:#fff;border-radius:50%;transition:0.3s;box-shadow:0 2px 6px rgba(0,0,0,0.2); }
        .toggle-switch input:checked + .toggle-slider { background:#DC2626; }
        .toggle-switch input:checked + .toggle-slider::before { transform:translateX(24px); }

        /* ===== CARDS ===== */
        .card { background:#fff;border-radius:18px;box-shadow:0 4px 20px rgba(0,0,0,0.07);padding:1.5rem;border:1.5px solid #fee2e2; }
        .stat-card { background:#fff;border-radius:18px;padding:1.25rem 1.5rem;box-shadow:0 4px 16px rgba(0,0,0,0.07);border:1.5px solid #fee2e2;display:flex;align-items:center;gap:1rem;transition:transform 0.2s,box-shadow 0.2s; }
        .stat-card:hover { transform:translateY(-3px);box-shadow:0 8px 24px rgba(220,38,38,0.12); }
        .stat-icon { width:52px;height:52px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;flex-shrink:0; }
        .stat-value { font-size:1.8rem;font-weight:900;color:#1f2937;line-height:1; }
        .stat-label { color:#6b7280;font-size:0.85rem;font-weight:600;margin-top:2px; }

        /* ===== BADGES ===== */
        .badge { display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:50px;font-size:0.78rem;font-weight:700; }
        .badge-green { background:#dcfce7;color:#15803d; }
        .badge-red   { background:#fee2e2;color:#b91c1c; }
        .badge-yellow{ background:#fef9c3;color:#a16207; }
        .badge-blue  { background:#dbeafe;color:#1d4ed8; }

        /* ===== TABLE ===== */
        .table-wrap { overflow-x:auto;border-radius:14px;border:1.5px solid #fee2e2; }
        table { width:100%;border-collapse:collapse; }
        table thead tr { background:linear-gradient(135deg,#DC2626,#b91c1c); }
        table thead th { color:#fff;padding:12px 16px;text-align:left;font-weight:700;font-size:0.86rem;white-space:nowrap; }
        table tbody tr { border-bottom:1px solid #fee2e2;transition:background 0.15s; }
        table tbody tr:last-child { border-bottom:none; }
        table tbody tr:hover { background:#fff5f5; }
        table tbody td { padding:11px 16px;font-size:0.9rem;color:#374151;vertical-align:middle; }

        /* ===== ALERT ===== */
        .alert { padding:11px 16px;border-radius:12px;font-weight:600;font-size:0.9rem;display:flex;align-items:center;gap:10px;margin-bottom:1rem; }
        .alert-success { background:#dcfce7;color:#15803d;border:1.5px solid #bbf7d0; }
        .alert-error   { background:#fee2e2;color:#b91c1c;border:1.5px solid #fecaca; }
        .alert-info    { background:#dbeafe;color:#1d4ed8;border:1.5px solid #bfdbfe; }
        .field-error   { color:#DC2626;font-size:0.8rem;font-weight:600;margin-top:4px; }

        /* ===== PAGINATION ===== */
        .pagination { display:flex;gap:6px;align-items:center;flex-wrap:wrap;margin-top:1rem; }
        .pagination a,.pagination span { padding:7px 12px;border-radius:9px;font-weight:700;font-size:0.85rem;text-decoration:none;border:1.5px solid #fca5a5;color:#DC2626;background:#fff;transition:all 0.2s; }
        .pagination a:hover { background:#DC2626;color:#fff;border-color:#DC2626; }
        .pagination span.active { background:#DC2626;color:#fff;border-color:#DC2626; }
        .pagination span.disabled { color:#9ca3af;border-color:#e5e7eb; }

        ::-webkit-scrollbar { width:6px;height:6px; }
        ::-webkit-scrollbar-track { background:#f9fafb; }
        ::-webkit-scrollbar-thumb { background:#fca5a5;border-radius:10px; }
        ::-webkit-scrollbar-thumb:hover { background:#DC2626; }

        @keyframes fadeInUp { from{opacity:0;transform:translateY(20px);}to{opacity:1;transform:translateY(0);} }
        .fade-in-up { animation:fadeInUp 0.5s ease forwards; }
    </style>
    @stack('head')
</head>
<body>

<!-- ====== SIDEBAR ====== -->
<div class="sidebar" id="sidebar">
    <a href="{{ route('home') }}" class="sidebar-brand">
        <span style="font-size:1.8rem;">🎯</span>
        <span class="sidebar-brand-text">KuisYuk!</span>
    </a>

    <div class="sidebar-user">
        <div class="sidebar-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
        <div>
            <div class="sidebar-username">{{ Str::limit(auth()->user()->name, 20) }}</div>
            <div class="sidebar-role">{{ auth()->user()->isAdmin() ? '👑 Admin' : '✏️ Kreator' }}</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        @if(auth()->user()->isAdmin())
            <div class="sidebar-section-title">Admin Panel</div>
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="icon">📊</span> Dashboard
            </a>
            <a href="{{ route('admin.users') }}" class="sidebar-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                <span class="icon">👥</span> Pengguna
            </a>
            <a href="{{ route('admin.admins') }}" class="sidebar-link {{ request()->routeIs('admin.admins*') ? 'active' : '' }}">
                <span class="icon">👑</span> Akun Admin
            </a>
        @else
            <div class="sidebar-section-title">Kreator</div>
            <a href="{{ route('creator.dashboard') }}" class="sidebar-link {{ request()->routeIs('creator.dashboard') ? 'active' : '' }}">
                <span class="icon">🏠</span> Dashboard
            </a>
            <a href="{{ route('creator.quizzes.index') }}" class="sidebar-link {{ request()->routeIs('creator.quizzes.index') ? 'active' : '' }}">
                <span class="icon">📋</span> Kuis Saya
            </a>
            <a href="{{ route('creator.quizzes.create') }}" class="sidebar-link {{ request()->routeIs('creator.quizzes.create') ? 'active' : '' }}">
                <span class="icon">➕</span> Buat Kuis Baru
            </a>
            <div class="sidebar-section-title">Akun</div>
            <a href="{{ route('creator.profile') }}" class="sidebar-link {{ request()->routeIs('creator.profile') ? 'active' : '' }}">
                <span class="icon">👤</span> Profil Saya
            </a>
        @endif
    </nav>

    <div class="sidebar-footer">
        <!-- Home opens in new tab -->
        <a href="{{ route('home') }}" class="sidebar-link" target="_blank" rel="noopener" style="margin-bottom:6px;">
            <span class="icon">🌐</span> Halaman Utama ↗
        </a>
        <form action="{{ route('logout') }}" method="POST" id="logoutForm">
            @csrf
            <button type="button" onclick="confirmLogout()" class="sidebar-link" style="background:rgba(255,255,255,0.1);">
                <span class="icon">🚪</span> Keluar
            </button>
        </form>
    </div>
</div>

<!-- Mobile overlay -->
<div id="overlay" onclick="closeSidebar()" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:49;"></div>

<!-- ====== MAIN ====== -->
<div class="main-content" id="mainContent">
    <div class="topbar">
        <div style="display:flex;align-items:center;gap:12px;">
            <button class="sidebar-toggle" onclick="toggleSidebar()">☰</button>
            <div>
                <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
                <div class="topbar-breadcrumb">@yield('breadcrumb', 'KuisYuk!')</div>
            </div>
        </div>
        <div style="display:flex;align-items:center;gap:10px;">
            @if(!auth()->user()->isAdmin())
            <a href="{{ route('creator.quizzes.create') }}" class="btn btn-primary btn-sm">➕ Buat Kuis</a>
            @endif
            <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#DC2626,#b91c1c);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:0.95rem;cursor:default;">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
        </div>
    </div>

    @if($errors->any())
    <div style="padding:1rem 1.5rem 0;">
        <div class="alert alert-error">
            <span>⚠️</span>
            <div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
        </div>
    </div>
    @endif

    <div class="page-content">
        @yield('content')
    </div>
</div>

<!-- ====== SCRIPTS ====== -->
<script>
function toggleSidebar() {
    const s = document.getElementById('sidebar');
    const o = document.getElementById('overlay');
    s.classList.toggle('open');
    o.style.display = s.classList.contains('open') ? 'block' : 'none';
}
function closeSidebar() {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('overlay').style.display = 'none';
}
function confirmLogout() {
    Swal.fire({
        title: 'Keluar?',
        text: 'Kamu yakin mau keluar dari KuisYuk!?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#DC2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: '🚪 Ya, Keluar',
        cancelButtonText: 'Batal'
    }).then(r => { if (r.isConfirmed) document.getElementById('logoutForm').submit(); });
}
</script>

@if(session('success'))
<script>document.addEventListener('DOMContentLoaded',()=>Swal.fire({icon:'success',title:'Berhasil!',text:'{{ addslashes(session('success')) }}',timer:3000,showConfirmButton:false}));</script>
@endif
@if(session('error'))
<script>document.addEventListener('DOMContentLoaded',()=>Swal.fire({icon:'error',title:'Oops!',text:'{{ addslashes(session('error')) }}'}));</script>
@endif

@stack('scripts')
</body>
</html>
