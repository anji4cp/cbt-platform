@extends('layouts.school_admin')

@section('content')

{{-- ================= HEADER ================= --}}
<div class="mb-6">
    <h1 class="text-xl font-semibold">Edit Siswa</h1>
    <p class="text-sm text-slate-500">
        Perbarui data siswa dengan benar.
    </p>
</div>

{{-- ================= FORM CARD ================= --}}
<div class="max-w-xl mx-auto bg-white rounded-xl shadow-sm p-6">

    <form method="POST" action="{{ route('school.students.update', $student) }}">
        @csrf
        @method('PUT')

        {{-- NAMA --}}
        <div class="mb-4">
            <label class="block text-sm font-medium text-slate-700 mb-1">
                Nama
            </label>
            <input
                type="text"
                name="name"
                value="{{ old('name', $student->name) }}"
                required
                class="w-full border rounded-lg px-3 py-2 text-sm
                       focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>

        {{-- KELAS --}}
        <div class="mb-6">
            <label class="block text-sm font-medium text-slate-700 mb-1">
                Kelas
            </label>
            <input
                type="text"
                name="class"
                value="{{ old('class', $student->class) }}"
                required
                class="w-full border rounded-lg px-3 py-2 text-sm
                       focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>

        {{-- ACTIONS --}}
        <div class="flex flex-col sm:flex-row gap-3 sm:justify-end">
            <a href="{{ route('school.students.index') }}"
               class="px-4 py-2 rounded-lg border text-center text-sm text-slate-600 hover:bg-slate-100">
                Batal
            </a>

            <button
                type="submit"
                class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm
                       hover:bg-indigo-700">
                Simpan Perubahan
            </button>
        </div>
    </form>

</div>

@endsection
