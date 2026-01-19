<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ?? 'CBT System' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100 text-gray-800 min-h-screen flex items-center justify-center">

    <main class="w-full px-4">
        {{-- SINGLE CARD CONTAINER --}}
        <div class="max-w-md mx-auto bg-white rounded-2xl shadow-md overflow-hidden">

            {{-- HEADER --}}
            <div class="border-b px-8 py-6 bg-slate-50 text-center">
                <h1 class="text-xl font-semibold">
                    {{ $heading ?? 'CBT Platform' }}
                </h1>
                @isset($subheading)
                    <p class="text-sm text-gray-500 mt-1">
                        {{ $subheading }}
                    </p>
                @endisset
            </div>

            {{-- CONTENT --}}
            <div class="px-8 py-6">
                {{-- SUCCESS --}}
                @if (session('success'))
                    <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- ERROR --}}
                @if ($errors->any())
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- SLOT --}}
                @yield('content')
            </div>

        </div>
    </main>

</body>
</html>
