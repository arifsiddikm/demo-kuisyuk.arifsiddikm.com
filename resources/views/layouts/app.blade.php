<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="KuisYuk! - Platform kuis online gratis. Buat kuis, bagikan link, dan kumpulkan jawaban dengan mudah dan menyenangkan!">
    <meta name="keywords" content="kuis online, buat kuis, quiz maker, kuis gratis, soal online">
    <meta name="author" content="KuisYuk!">
    <meta property="og:title" content="KuisYuk! - Buat & Bagikan Kuis Online">
    <meta property="og:description" content="Platform kuis online paling seru! Buat kuis, bagikan link, dan lihat hasilnya.">
    <meta property="og:type" content="website">
    <title>@yield('title', 'KuisYuk! - Platform Kuis Online Seru')</title>

    <!-- Favicon SVG inline -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' rx='20' fill='%23DC2626'/><text y='.9em' font-size='70' x='10'>🎯</text></svg>">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- CKEditor 5 -->
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&family=Fredoka+One&display=swap" rel="stylesheet">

    <style>
        * { box-sizing: border-box; }

        body {
            font-family: 'Nunito', sans-serif;
            background-color: #fff5f5;
        }

        .font-fredoka { font-family: 'Fredoka One', cursive; }

        /* === BUBBLE BACKGROUND === */
        .bubble-bg {
            background-color: #DC2626;
            position: relative;
            overflow: hidden;
        }
        .bubble-bg::before {
            content: '';
            position: absolute;
            width: 300px; height: 300px;
            background: rgba(255,255,255,0.08);
            border-radius: 50%;
            top: -80px; left: -80px;
        }
        .bubble-bg::after {
            content: '';
            position: absolute;
            width: 200px; height: 200px;
            background: rgba(255,255,255,0.06);
            border-radius: 50%;
            bottom: -60px; right: -40px;
        }

        /* === NAVBAR === */
        .navbar {
            background: #DC2626;
            box-shadow: 0 4px 20px rgba(220,38,38,0.3);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .navbar-brand {
            font-family: 'Fredoka One', cursive;
            font-size: 1.8rem;
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .navbar-brand span.emoji { font-size: 1.5rem; }

        /* === BUTTONS === */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 10px 22px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.95rem;
            cursor: pointer;
            border: none;
            transition: all 0.2s ease;
            text-decoration: none;
            line-height: 1.2;
        }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(0,0,0,0.15); }
        .btn:active { transform: translateY(0); }

        .btn-primary {
            background: linear-gradient(135deg, #DC2626, #b91c1c);
            color: #fff;
            box-shadow: 0 4px 12px rgba(220,38,38,0.4);
        }
        .btn-primary:hover { background: linear-gradient(135deg, #b91c1c, #991b1b); }

        .btn-white {
            background: #fff;
            color: #DC2626;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .btn-white:hover { background: #fff5f5; }

        .btn-success {
            background: linear-gradient(135deg, #16a34a, #15803d);
            color: #fff;
            box-shadow: 0 4px 12px rgba(22,163,74,0.4);
        }
        .btn-danger {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            color: #fff;
            box-shadow: 0 4px 12px rgba(220,38,38,0.3);
        }
        .btn-warning {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #fff;
            box-shadow: 0 4px 12px rgba(245,158,11,0.3);
        }
        .btn-secondary {
            background: #f3f4f6;
            color: #374151;
            border: 2px solid #e5e7eb;
        }
        .btn-secondary:hover { background: #e5e7eb; }
        .btn-sm { padding: 6px 14px; font-size: 0.82rem; }
        .btn-lg { padding: 14px 32px; font-size: 1.1rem; }
        .btn-outline-white {
            background: transparent;
            color: #fff;
            border: 2px solid rgba(255,255,255,0.7);
        }
        .btn-outline-white:hover { background: rgba(255,255,255,0.15); }

        /* === FORM INPUTS === */
        .form-group { margin-bottom: 1.25rem; }
        .form-label {
            display: block;
            font-weight: 700;
            color: #374151;
            margin-bottom: 0.4rem;
            font-size: 0.92rem;
        }
        .form-input {
            width: 100%;
            padding: 11px 16px;
            border: 2px solid #fca5a5;
            border-radius: 14px;
            font-size: 0.95rem;
            font-family: 'Nunito', sans-serif;
            color: #1f2937;
            background: #fff;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }
        .form-input:focus {
            border-color: #DC2626;
            box-shadow: 0 0 0 4px rgba(220,38,38,0.12);
        }
        .form-input::placeholder { color: #9ca3af; }

        .form-textarea {
            width: 100%;
            padding: 11px 16px;
            border: 2px solid #fca5a5;
            border-radius: 14px;
            font-size: 0.95rem;
            font-family: 'Nunito', sans-serif;
            color: #1f2937;
            background: #fff;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
            resize: vertical;
            min-height: 100px;
        }
        .form-textarea:focus {
            border-color: #DC2626;
            box-shadow: 0 0 0 4px rgba(220,38,38,0.12);
        }

        .form-select {
            width: 100%;
            padding: 11px 16px;
            border: 2px solid #fca5a5;
            border-radius: 14px;
            font-size: 0.95rem;
            font-family: 'Nunito', sans-serif;
            color: #1f2937;
            background: #fff;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%236b7280' stroke-width='1.5' d='M6 8l4 4 4-4'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 20px;
            padding-right: 44px;
        }
        .form-select:focus {
            border-color: #DC2626;
            box-shadow: 0 0 0 4px rgba(220,38,38,0.12);
        }

        /* Radio & Checkbox */
        .form-radio-group, .form-check-group { display: flex; flex-wrap: wrap; gap: 10px; }
        .form-radio-label, .form-check-label {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-weight: 600;
            color: #374151;
            padding: 8px 16px;
            border: 2px solid #fca5a5;
            border-radius: 50px;
            transition: all 0.2s;
            background: #fff;
        }
        .form-radio-label:hover, .form-check-label:hover { border-color: #DC2626; background: #fff5f5; }
        .form-radio-label input[type="radio"],
        .form-check-label input[type="checkbox"] {
            width: 16px; height: 16px;
            accent-color: #DC2626;
            cursor: pointer;
        }
        input[type="radio"]:checked + span,
        input[type="checkbox"]:checked + span { color: #DC2626; }

        /* Toggle Switch */
        .toggle-switch { position: relative; display: inline-block; width: 52px; height: 28px; }
        .toggle-switch input { opacity: 0; width: 0; height: 0; }
        .toggle-slider {
            position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0;
            background: #d1d5db; border-radius: 28px; transition: 0.3s;
        }
        .toggle-slider::before {
            position: absolute; content: '';
            height: 20px; width: 20px;
            left: 4px; bottom: 4px;
            background: #fff; border-radius: 50%;
            transition: 0.3s;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        }
        .toggle-switch input:checked + .toggle-slider { background: #DC2626; }
        .toggle-switch input:checked + .toggle-slider::before { transform: translateX(24px); }

        /* === CARDS === */
        .card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            padding: 1.5rem;
            border: 1.5px solid #fee2e2;
        }
        .card-hover { transition: transform 0.2s, box-shadow 0.2s; }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 10px 30px rgba(220,38,38,0.15); }

        /* === BADGE === */
        .badge {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 3px 10px; border-radius: 50px;
            font-size: 0.78rem; font-weight: 700;
        }
        .badge-green { background: #dcfce7; color: #15803d; }
        .badge-red { background: #fee2e2; color: #b91c1c; }
        .badge-yellow { background: #fef9c3; color: #a16207; }
        .badge-blue { background: #dbeafe; color: #1d4ed8; }
        .badge-purple { background: #f3e8ff; color: #7c3aed; }

        /* === ALERTS === */
        .alert {
            padding: 12px 18px; border-radius: 14px;
            font-weight: 600; font-size: 0.92rem;
            display: flex; align-items: center; gap: 10px; margin-bottom: 1rem;
        }
        .alert-success { background: #dcfce7; color: #15803d; border: 1.5px solid #bbf7d0; }
        .alert-error { background: #fee2e2; color: #b91c1c; border: 1.5px solid #fecaca; }
        .alert-info { background: #dbeafe; color: #1d4ed8; border: 1.5px solid #bfdbfe; }

        /* === TABLE === */
        .table-wrap { overflow-x: auto; border-radius: 16px; border: 1.5px solid #fee2e2; }
        table { width: 100%; border-collapse: collapse; }
        table thead tr { background: linear-gradient(135deg, #DC2626, #b91c1c); }
        table thead th { color: #fff; padding: 13px 16px; text-align: left; font-weight: 700; font-size: 0.88rem; white-space: nowrap; }
        table tbody tr { border-bottom: 1px solid #fee2e2; transition: background 0.15s; }
        table tbody tr:last-child { border-bottom: none; }
        table tbody tr:hover { background: #fff5f5; }
        table tbody td { padding: 12px 16px; font-size: 0.92rem; color: #374151; vertical-align: middle; }

        /* === PAGINATION === */
        .pagination { display: flex; gap: 6px; align-items: center; flex-wrap: wrap; margin-top: 1rem; }
        .pagination a, .pagination span {
            padding: 7px 13px; border-radius: 10px;
            font-weight: 700; font-size: 0.88rem; text-decoration: none;
            border: 1.5px solid #fca5a5; color: #DC2626; background: #fff;
            transition: all 0.2s;
        }
        .pagination a:hover { background: #DC2626; color: #fff; border-color: #DC2626; }
        .pagination span.active { background: #DC2626; color: #fff; border-color: #DC2626; }
        .pagination span.disabled { color: #9ca3af; border-color: #e5e7eb; cursor: not-allowed; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f9fafb; }
        ::-webkit-scrollbar-thumb { background: #fca5a5; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #DC2626; }

        /* Bubble decorations */
        .bubble-deco {
            position: absolute; border-radius: 50%;
            background: rgba(255,255,255,0.1);
            pointer-events: none;
        }

        /* === ANIMATIONS === */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-12px); }
        }
        @keyframes wiggle {
            0%, 100% { transform: rotate(-2deg); }
            50% { transform: rotate(2deg); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-30px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes pulse-bubble {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        .float-anim { animation: float 3s ease-in-out infinite; }
        .wiggle-anim { animation: wiggle 2s ease-in-out infinite; }
        .fade-in-up { animation: fadeInUp 0.6s ease forwards; }
        .slide-in-left { animation: slideInLeft 0.5s ease forwards; }

        /* Error/validation */
        .field-error { color: #DC2626; font-size: 0.82rem; font-weight: 600; margin-top: 4px; display: flex; align-items: center; gap: 4px; }
    </style>

    @stack('head')
</head>
<body>
@yield('content')

@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session('success') }}', timer: 3000, showConfirmButton: false, customClass: { popup: 'rounded-2xl' } });
    });
</script>
@endif
@if(session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({ icon: 'error', title: 'Oops!', text: '{{ session('error') }}', customClass: { popup: 'rounded-2xl' } });
    });
</script>
@endif

@stack('scripts')
</body>
</html>
