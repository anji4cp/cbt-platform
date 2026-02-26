<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'CBT Siswa' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' rx='20' fill='%232563eb'/><text x='50' y='70' font-size='60' font-weight='bold' text-anchor='middle' fill='white'>C</text></svg>">

    @php
        $theme = session('school_brand.theme') ?? '#2563eb';
    @endphp

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --theme: {{ $theme }};
        }
    </style>
</head>

<body class="bg-slate-100 text-gray-800 min-h-screen flex flex-col">

    {{-- TOPBAR --}}
    <header
        class="border-b"
        style="background-color: var(--theme); color: white;">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">

            {{-- BRAND --}}
            <div class="flex items-center gap-3">
                @if(session('school_brand.logo'))
                    <img src="{{ asset('storage/' . session('school_brand.logo')) }}"
                         class="h-10 w-10 rounded-lg object-cover bg-white p-1">
                @endif

                <div>
                    <div class="font-semibold leading-tight">
                        {{ session('school_brand.name') ?? 'CBT Sekolah' }}
                    </div>
                    <div class="text-xs opacity-90">
                        Computer Based Test
                    </div>
                </div>
            </div>

            {{-- ACTION --}}
            <div>
                @if(request()->routeIs('student.login.form'))
                    <a href="{{ route('student.server.form') }}"
                       class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold bg-white text-gray-800 hover:bg-slate-100 transition">
                        Ganti Server
                    </a>
                @else
                    <form method="POST" action="{{ route('student.logout') }}">
                        @csrf
                        <button
                            class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-semibold bg-red-600 hover:bg-red-700 text-white">
                            Logout
                        </button>
                    </form>
                @endif
            </div>

        </div>
    </header>

    {{-- CONTENT --}}
    <main class="flex-1 flex items-center justify-center px-4 py-8">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="text-center text-xs text-gray-400 py-4">
        © {{ date('Y') }} CBT Platform
    </footer>

</body>
</html>
