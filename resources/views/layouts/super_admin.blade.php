<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Super Admin - CBT</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @vite('resources/css/app.css')
</head>
<body class="bg-slate-100">

<div class="flex min-h-screen">

    <!-- SIDEBAR -->
    <aside id="sidebar"
           class="w-64 bg-gradient-to-b from-slate-900 to-slate-800
                  text-slate-200 p-5 transition-all duration-300">

        <!-- HEADER -->
        <div class="flex items-center justify-between mb-6">
            <h2 id="sidebarTitle"
                class="text-sm font-semibold tracking-wide uppercase text-slate-300">
                Super Admin
            </h2>

            <button onclick="toggleSidebar()"
                    class="text-slate-300 hover:text-white text-lg">
                ☰
            </button>
        </div>

        <!-- MENU -->
        <nav class="space-y-1 text-sm">

            {{-- DASHBOARD --}}
            <a href="{{ route('superadmin.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2 rounded
               {{ request()->routeIs('superadmin.dashboard')
                    ? 'bg-indigo-600 text-white'
                    : 'hover:bg-slate-700' }}">
                <span>📊</span>
                <span class="menu-text">Dashboard</span>
            </a>

            {{-- SEKOLAH --}}
            <a href="{{ route('superadmin.schools.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded
               {{ request()->routeIs('superadmin.schools.*')
                    ? 'bg-indigo-600 text-white'
                    : 'hover:bg-slate-700' }}">
                <span>🏫</span>
                <span class="menu-text">Manajemen Sekolah</span>
            </a>

            {{-- MONITORING --}}
            <a href="{{ route('superadmin.monitoring') }}"
               class="flex items-center gap-3 px-3 py-2 rounded
               {{ request()->routeIs('superadmin.monitoring')
                    ? 'bg-indigo-600 text-white'
                    : 'hover:bg-slate-700' }}">
                <span>📡</span>
                <span class="menu-text">Monitoring</span>
            </a>

        </nav>

        <!-- LOGOUT -->
        <form method="POST" action="{{ route('logout') }}" class="mt-8">
            @csrf
            <button
                class="flex items-center gap-3 w-full px-3 py-2 rounded
                       hover:bg-red-600 hover:text-white">
                <span>🚪</span>
                <span class="menu-text">Logout</span>
            </button>
        </form>
    </aside>

    <!-- CONTENT -->
    <main class="flex-1 p-6 transition-all duration-300">

        <!-- TOP BAR -->
        <div class="mb-6 bg-white rounded-xl shadow-sm p-4 flex justify-between items-center">
            <div class="text-sm text-gray-600">
                Login sebagai:
                <strong class="text-gray-800">
                    {{ auth()->user()->email }}
                </strong>
            </div>

            <div class="text-xs text-gray-400">
                Super Admin Panel
            </div>
        </div>

        @yield('content')
    </main>

</div>

<!-- SIDEBAR TOGGLE SCRIPT -->
<script>
    let collapsed = false;

    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const texts = document.querySelectorAll('.menu-text');
        const title = document.getElementById('sidebarTitle');

        collapsed = !collapsed;

        if (collapsed) {
            sidebar.classList.replace('w-64', 'w-20');
            title.classList.add('hidden');
            texts.forEach(t => t.classList.add('hidden'));
        } else {
            sidebar.classList.replace('w-20', 'w-64');
            title.classList.remove('hidden');
            texts.forEach(t => t.classList.remove('hidden'));
        }
    }
</script>

</body>
</html>
