<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Sekolah - CBT</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @vite('resources/css/app.css')
</head>
<body class="bg-slate-100">

<div class="flex min-h-screen">

    <!-- SIDEBAR -->
    <aside id="sidebar"
           class="w-64 bg-gradient-to-b from-slate-900 to-slate-800
                  text-slate-200 p-5 transition-all duration-300">

        <!-- LOGO / TITLE -->
        <div class="flex items-center justify-between mb-6">
            <h2 id="sidebarTitle"
                class="text-sm font-semibold tracking-wide uppercase text-slate-300">
                Admin Sekolah
            </h2>

            <!-- TOGGLE BUTTON -->
            <button onclick="toggleSidebar()"
                    class="text-slate-300 hover:text-white">
                <!-- icon menu -->
                ☰
            </button>
        </div>

        <!-- MENU -->
        <nav class="space-y-1 text-sm">

            <a href="{{ route('school.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2 rounded
               {{ request()->is('school-admin/dashboard*')
                    ? 'bg-indigo-600 text-white'
                    : 'hover:bg-slate-700' }}">
                <span>📊</span>
                <span class="menu-text">Dashboard</span>
            </a>

            <a href="{{ route('school.profile') }}"
               class="flex items-center gap-3 px-3 py-2 rounded
               {{ request()->is('school-admin/profile*')
                    ? 'bg-indigo-600 text-white'
                    : 'hover:bg-slate-700' }}">
                <span>🏫</span>
                <span class="menu-text">Profil Sekolah</span>
            </a>

            <a href="{{ route('school.students.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded
               {{ request()->is('school-admin/students*')
                    ? 'bg-indigo-600 text-white'
                    : 'hover:bg-slate-700' }}">
                <span>👨‍🎓</span>
                <span class="menu-text">Siswa</span>
            </a>

            <a href="{{ route('school.exam-cards.index') }}"
            class="flex items-center gap-3 px-3 py-2 rounded
            {{ request()->routeIs('exam-cards.*')
                    ? 'bg-indigo-600 text-white'
                    : 'hover:bg-slate-700 text-slate-200' }}">
                <span>🪪</span>
                <span class="menu-text">Kartu Ujian</span>
            </a>


            <a href="{{ route('school.exams.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded
               {{ request()->is('school-admin/exams*')
                    ? 'bg-indigo-600 text-white'
                    : 'hover:bg-slate-700' }}">
                <span>📝</span>
                <span class="menu-text">Ujian</span>
            </a>

            <a href="{{ route('school.reports.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded
               {{ request()->is('school-admin/reports*')
                    ? 'bg-indigo-600 text-white'
                    : 'hover:bg-slate-700' }}">
                <span>📈</span>
                <span class="menu-text">Laporan Nilai</span>
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
                Sekolah :
                <strong class="text-gray-800">
                    {{ auth()->user()->school->name ?? 'CBT Sekolah' }}
                </strong>
            </div>

            <div class="text-xs text-gray-400">
                {{ auth()->user()->email }}
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
            sidebar.classList.remove('w-64');
            sidebar.classList.add('w-20');
            title.classList.add('hidden');
            texts.forEach(t => t.classList.add('hidden'));
        } else {
            sidebar.classList.add('w-64');
            sidebar.classList.remove('w-20');
            title.classList.remove('hidden');
            texts.forEach(t => t.classList.remove('hidden'));
        }
    }
</script>

</body>
</html>
