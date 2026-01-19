@extends('layouts.student')

@section('content')

<div class="flex items-center justify-center w-full">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-md overflow-hidden">

        {{-- HEADER --}}
        <div class="border-b px-8 py-6 bg-slate-50 text-center">
            <h1 class="text-xl font-semibold text-gray-800">
                Login Siswa
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                {{ session('school_brand.name') }}
            </p>
        </div>

        {{-- ERROR --}}
        @if ($errors->any())
            <div class="mx-8 mt-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- FORM --}}
        <form method="POST"
              action="{{ route('student.login') }}"
              class="px-8 py-6 space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Username
                </label>
                <input type="text"
                       name="username"
                       value="{{ old('username') }}"
                       required
                       autofocus
                       class="w-full rounded-lg border px-3 py-2 focus:ring-2 focus:ring-indigo-200">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Password
                </label>
                <input type="password"
                       name="password"
                       required
                       class="w-full rounded-lg border px-3 py-2 focus:ring-2 focus:ring-indigo-200">
            </div>

            <button type="submit"
                    class="w-full text-white py-2 rounded-lg font-semibold transition"
                    style="background: {{ session('school_brand.theme') ?? '#2563eb' }}">
                Masuk Ujian
            </button>
        </form>

        {{-- FOOTER --}}
        <div class="px-8 pb-6 text-center text-xs text-gray-400">
            CBT Sekolah • Sistem Ujian Berbasis Komputer
        </div>

    </div>
</div>

@endsection
