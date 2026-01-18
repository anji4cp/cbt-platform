<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>CBT Siswa</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @php
        $theme = session('school_brand.theme') ?? '#2563eb';
    @endphp

    <style>
        :root {
            --theme: {{ $theme }};
            --theme-dark: #1e40af;
        }

        body {
            margin: 0;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f8fafc;
            color: #0f172a;
        }

        /* TOPBAR */
        .topbar {
            background: linear-gradient(135deg, var(--theme), var(--theme-dark));
            color: #fff;
            padding: 14px 22px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand img {
            height: 42px;
            width: 42px;
            object-fit: cover;
            border-radius: 10px;
            background: #fff;
            padding: 4px;
        }

        .brand-name {
            font-size: 17px;
            font-weight: 700;
            line-height: 1.2;
        }

        .brand-sub {
            font-size: 11px;
            opacity: .85;
        }

        /* CONTENT */
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: #f1f5f9;
        }

        .student-root {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .student-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }


        /* BUTTON */
        .btn {
            padding: 8px 14px;
            background: var(--theme);
            color: white;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            font-size: 13px;
        }

        .btn:hover {
            opacity: .9;
        }

        .btn-danger {
            background: #dc2626;
        }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 12px;
            overflow: hidden;
        }

        th, td {
            border-bottom: 1px solid #e5e7eb;
            padding: 10px 12px;
            text-align: left;
            font-size: 13px;
        }

        th {
            background: #f1f5f9;
            font-weight: 600;
        }

        tr:last-child td {
            border-bottom: none;
        }

        /* LINK */
        a {
            color: var(--theme);
            text-decoration: none;
            font-weight: 600;
        }

        a:hover {
            text-decoration: underline;
        }

        {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: #f1f5f9;
        }

        .student-root {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .student-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
    </style>
</head>
<body>

{{-- TOPBAR --}}
<div class="topbar">

    {{-- KIRI: LOGO + NAMA SEKOLAH --}}
    <div class="brand">
        @if(session('school_brand.logo'))
            <img src="{{ asset('storage/' . session('school_brand.logo')) }}">
        @endif

        <div>
            <div class="brand-name">
                {{ session('school_brand.name') ?? 'CBT Sekolah' }}
            </div>
            <div class="brand-sub">
                Computer Based Test
            </div>
        </div>
    </div>

    {{-- KANAN: LOGOUT --}}
    @if(request()->routeIs('student.login.form'))
        <!-- KHUSUS HALAMAN LOGIN SISWA -->
        <a href="{{ route('student.server.form') }}"
        class="btn"
        style="background:#0ea5e9">
            Ganti Server
        </a>
    @else
        <!-- HALAMAN LAIN -->
        <form method="POST" action="{{ route('student.logout') }}">
            @csrf
            <button class="btn btn-danger">
                Logout
            </button>
        </form>
    @endif
</div>

{{-- CONTENT --}}
<div class="container">
    @yield('content')
</div>

</body>
</html>
