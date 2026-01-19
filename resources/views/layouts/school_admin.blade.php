<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Sekolah - CBT</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite('resources/css/app.css')
</head>

<body class="bg-slate-100 overflow-hidden">

<!-- ================= SIDEBAR ================= -->
<aside
    id="sidebar"
    class="
        fixed inset-y-0 left-0 z-40 w-64
        bg-gradient-to-b from-slate-900 to-slate-800
        text-slate-200 p-5
        transform -translate-x-full md:translate-x-0
        transition-transform duration-300
    "
>
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-sm font-semibold uppercase tracking-wide text-slate-300">
            Admin Sekolah
        </h2>

        <!-- CLOSE MOBILE -->
        <button class="md:hidden text-xl" onclick="closeSidebar()">✕</button>
    </div>

    {{-- MENU --}}
    <nav class="space-y-1 text-sm">
        @php
            $menus = [
                ['📊','Dashboard','school.dashboard','school-admin/dashboard*'],
                ['🏫','Profil Sekolah','school.profile','school-admin/profile*'],
                ['👨‍🎓','Siswa','school.students.index','school-admin/students*'],
                ['🪪','Kartu Ujian','school.exam-cards.index','exam-cards.*'],
                ['📝','Ujian','school.exams.index','school-admin/exams*'],
                ['📈','Laporan Nilai','school.reports.index','school-admin/reports*'],
            ];
        @endphp

        @foreach($menus as [$icon,$label,$route,$active])
            <a href="{{ route($route) }}"
               class="flex items-center gap-3 px-3 py-2 rounded transition
               {{ request()->is($active) || request()->routeIs($active)
                    ? 'bg-indigo-600 text-white'
                    : 'hover:bg-slate-700 text-slate-200' }}">
                <span>{{ $icon }}</span>
                <span>{{ $label }}</span>
            </a>
        @endforeach
    </nav>

    {{-- LOGOUT --}}
    <form method="POST" action="{{ route('logout') }}" class="mt-8">
        @csrf
        <button class="w-full px-3 py-2 rounded hover:bg-red-600">
            🚪 Logout
        </button>
    </form>
</aside>

<!-- OVERLAY MOBILE -->
<div id="overlay"
     class="fixed inset-0 bg-black/40 z-30 hidden md:hidden"
     onclick="closeSidebar()"></div>

<!-- ================= TOPBAR ================= -->
<header
    class="
        fixed top-0 left-0 md:left-64 right-0 z-20
        h-14 bg-white shadow-sm
        flex items-center justify-between px-4
    "
>
    <button onclick="openSidebar()" class="md:hidden text-xl text-gray-700">
        ☰
    </button>

    <div class="text-sm text-gray-600">
        Sekolah :
        <strong class="text-gray-800">
            {{ auth()->user()->school->name ?? 'CBT Sekolah' }}
        </strong>
    </div>

    <div class="hidden sm:block text-xs text-gray-400">
        {{ auth()->user()->email }}
    </div>
</header>

<!-- ================= CONTENT ================= -->
<main
    class="
        absolute top-14 left-0 md:left-64 right-0 bottom-0
        overflow-y-auto
        p-6
    "
>
    @yield('content')
</main>

<!-- ================= SCRIPT ================= -->
<script>
    function openSidebar() {
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
    }

    function closeSidebar() {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
    }

    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
</script>

</body>
</html>
