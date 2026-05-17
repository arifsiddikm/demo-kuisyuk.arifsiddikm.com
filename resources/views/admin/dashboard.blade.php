@extends('layouts.dashboard')
@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard')
@section('breadcrumb', 'KuisYuk! / Admin')

@section('content')
<!-- Greeting -->
<div style="background:linear-gradient(135deg,#1f2937,#111827);border-radius:20px;padding:1.75rem 2rem;margin-bottom:1.75rem;position:relative;overflow:hidden;">
    <div style="position:absolute;top:-40px;right:-40px;width:160px;height:160px;border-radius:50%;background:rgba(220,38,38,0.15);"></div>
    <div style="position:relative;z-index:1;">
        <div style="font-family:'Fredoka One',cursive;font-size:1.5rem;color:#fff;margin-bottom:0.25rem;">
            👑 Panel Admin KuisYuk!
        </div>
        <div style="color:rgba(255,255,255,0.7);font-weight:600;font-size:0.92rem;">
            Monitoring keseluruhan platform KuisYuk!
        </div>
    </div>
</div>

<!-- Stats -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1.25rem;margin-bottom:1.75rem;">
    <div class="stat-card">
        <div class="stat-icon" style="background:linear-gradient(135deg,#dbeafe,#bfdbfe);">👥</div>
        <div>
            <div class="stat-value">{{ $totalUsers }}</div>
            <div class="stat-label">Total Kreator</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:linear-gradient(135deg,#fee2e2,#fecaca);">📋</div>
        <div>
            <div class="stat-value">{{ $totalQuizzes }}</div>
            <div class="stat-label">Total Kuis</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:linear-gradient(135deg,#dcfce7,#bbf7d0);">✅</div>
        <div>
            <div class="stat-value">{{ $activeQuizzes }}</div>
            <div class="stat-label">Kuis Aktif</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:linear-gradient(135deg,#f3e8ff,#e9d5ff);">✏️</div>
        <div>
            <div class="stat-value">{{ $totalResponses }}</div>
            <div class="stat-label">Total Jawaban</div>
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem;">
    <!-- Recent Users -->
    <div class="card">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;">
            <h3 style="font-weight:900;color:#1f2937;font-size:1rem;">👥 Kreator Terbaru</h3>
            <a href="{{ route('admin.users') }}" class="btn btn-secondary btn-sm">Lihat Semua</a>
        </div>
        @foreach($recentUsers as $user)
        <div style="display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1.5px solid #fee2e2;">
            <div style="width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,#DC2626,#b91c1c);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;flex-shrink:0;">
                {{ strtoupper(substr($user->name,0,1)) }}
            </div>
            <div style="flex:1;">
                <div style="font-weight:800;color:#1f2937;font-size:0.9rem;">{{ $user->name }}</div>
                <div style="font-size:0.78rem;color:#9ca3af;font-weight:600;">{{ $user->email }}</div>
            </div>
            <span class="badge {{ $user->is_active ? 'badge-green' : 'badge-red' }}">
                {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
            </span>
        </div>
        @endforeach
    </div>

    <!-- Quick Links -->
    <div style="display:flex;flex-direction:column;gap:1rem;">
        <div class="card" style="text-align:center;padding:1.5rem;">
            <div style="font-size:2rem;margin-bottom:0.75rem;">👥</div>
            <div style="font-weight:800;color:#1f2937;margin-bottom:0.75rem;">Kelola Pengguna</div>
            <a href="{{ route('admin.users') }}" class="btn btn-primary" style="width:100%;justify-content:center;">Buka</a>
        </div>
        <div class="card" style="text-align:center;padding:1.5rem;">
            <div style="font-size:2rem;margin-bottom:0.75rem;">👑</div>
            <div style="font-weight:800;color:#1f2937;margin-bottom:0.75rem;">Akun Admin</div>
            <a href="{{ route('admin.admins') }}" class="btn btn-secondary" style="width:100%;justify-content:center;">Buka</a>
        </div>
        <div class="card" style="text-align:center;padding:1.5rem;">
            <div style="font-size:2rem;margin-bottom:0.75rem;">📥</div>
            <div style="font-weight:800;color:#1f2937;margin-bottom:0.75rem;">Export Data</div>
            <a href="{{ route('admin.users.export') }}" class="btn btn-success" style="width:100%;justify-content:center;">Download Excel</a>
        </div>
    </div>
</div>
@endsection
