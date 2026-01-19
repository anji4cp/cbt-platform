@extends('layouts.student')

@section('content')

<div class="flex items-center justify-center w-full">

    <div class="w-full max-w-xl bg-white rounded-2xl shadow-md overflow-hidden">

        {{-- HEADER --}}
        <div class="border-b px-8 py-6 bg-slate-50 text-center">
            <h1 class="text-xl font-semibold text-gray-800">
                Token Ujian
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Masukkan token untuk mulai ujian
            </p>
        </div>

        {{-- ERROR --}}
        @if($errors->any())
            <div class="mx-8 mt-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm text-center">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- FORM --}}
        <form method="POST"
              action="{{ route('student.exam.token', $exam->id) }}"
              class="px-8 py-6 space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1 text-center">
                    Token Ujian
                </label>

                <input
                    type="text"
                    name="token"
                    placeholder="••••••"
                    required
                    autofocus
                    class="w-full text-center tracking-widest rounded-lg border px-3 py-2
                           focus:ring-2 focus:ring-indigo-200">
            </div>

            <button type="submit"
                    class="w-full text-white py-2 rounded-lg font-semibold transition"
                    style="background: {{ session('school_brand.theme') ?? '#2563eb' }}">
                Mulai Ujian
            </button>
        </form>

        {{-- FOOTER --}}
        <div class="px-8 pb-6 text-center text-xs text-gray-400">
            CBT Sekolah • Sistem Ujian Berbasis Komputer
        </div>

    </div>

</div>

@endsection
