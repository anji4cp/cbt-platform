@extends('layouts.school_admin')

@section('content')

{{-- ================= HEADER ================= --}}
<div class="mb-6 text-center">
    <h1 class="text-xl font-semibold">Tambah Siswa</h1>
    <p class="text-sm text-slate-500">
        Masukkan data siswa dengan benar.
    </p>
</div>

{{-- ================= FORM CARD ================= --}}
<div class="flex justify-center">
    <div class="w-full max-w-xl bg-white rounded-xl shadow-sm p-6">

        {{-- ERROR --}}
        @if ($errors->any())
            <div class="mb-4 rounded-lg bg-red-50 border border-red-200 p-4 text-sm">
                <div class="font-semibold text-red-800 mb-2">⚠️ Gagal menambahkan siswa:</div>
                <ul class="space-y-1">
                    @foreach ($errors->all() as $error)
                        <li class="text-red-700 flex items-start">
                            <span class="mr-2">•</span>
                            <span>{{ $error }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST"
              action="{{ route('school.students.store') }}"
              class="space-y-4">
            @csrf

            {{-- NAMA --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Nama Siswa
                </label>
                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    placeholder="Nama lengkap siswa"
                    class="w-full border rounded-lg px-3 py-2 text-sm
                           focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            {{-- USERNAME --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Username
                </label>
                <input
                    type="text"
                    name="username"
                    value="{{ old('username') }}"
                    required
                    placeholder="Username login siswa"
                    class="w-full border rounded-lg px-3 py-2 text-sm
                           focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            {{-- KELAS --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Kelas
                </label>
                <input
                    type="text"
                    name="class"
                    value="{{ old('class') }}"
                    required
                    placeholder="Contoh: X IPA 1"
                    class="w-full border rounded-lg px-3 py-2 text-sm
                           focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            {{-- PASSWORD --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">
                    Password
                </label>
                <input
                    type="password"
                    name="password"
                    required
                    placeholder="Masukkan password"
                    class="w-full border rounded-lg px-3 py-2 text-sm
                           focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            {{-- ACTIONS --}}
            <div class="flex flex-col sm:flex-row gap-3 pt-4 sm:justify-end">
                <a href="{{ route('school.students.index') }}"
                   class="px-6 py-2 rounded-lg border text-center text-sm
                          text-slate-600 hover:bg-slate-100">
                    Batal
                </a>

                <button
                    type="submit"
                    class="bg-indigo-600 text-white px-6 py-2 rounded-lg text-sm
                           hover:bg-indigo-700">
                    Simpan
                </button>
            </div>

        </form>
    </div>
</div>

@endsection
