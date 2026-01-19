<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Super Admin - CBT</title>
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
    <!-- HEADER -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-sm font-semibold uppercase tracking-wide text-slate-300">
            Super Admin
        </h2>

        <!-- CLOSE MOBILE -->
        <button class="md:hidden text-xl" onclick="closeSidebar()">✕</button>
    </div>

    <!-- MENU -->
    <nav class="space-y-1 text-sm">

        <a href="{{ route('superadmin.dashboard') }}"
           class="flex items-center gap-3 px-3 py-2 rounded transition
           {{ request()->routeIs('superadmin.dashboard')
                ? 'bg-indigo-600 text-white'
                : 'hover:bg-slate-700 text-slate-200' }}">
            <span>📊</span>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('superadmin.schools.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded transition
           {{ request()->routeIs('superadmin.schools.*')
                ? 'bg-indigo-600 text-white'
                : 'hover:bg-slate-700 text-slate-200' }}">
            <span>🏫</span>
            <span>Manajemen Sekolah</span>
        </a>

        <a href="{{ route('superadmin.monitoring') }}"
           class="flex items-center gap-3 px-3 py-2 rounded transition
           {{ request()->routeIs('superadmin.monitoring')
                ? 'bg-indigo-600 text-white'
                : 'hover:bg-slate-700 text-slate-200' }}">
            <span>📡</span>
            <span>Monitoring</span>
        </a>

    </nav>

    <!-- LOGOUT -->
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
        Login sebagai:
        <strong class="text-gray-800">
            {{ auth()->user()->email }}
        </strong>
    </div>

    <div class="hidden sm:block text-xs text-gray-400">
        Super Admin Panel
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
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');

    function openSidebar() {
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
    }

    function closeSidebar() {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
    }
</script>

</body>
</html>
