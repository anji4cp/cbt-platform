@extends('layouts.school_admin')

@section('content')
<div class="max-w-xl mx-auto bg-white rounded-xl shadow p-6">
    <h2 class="text-lg font-semibold mb-4">Edit Siswa</h2>

    <form method="POST" action="{{ route('school.students.update', $student) }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="text-sm">Nama</label>
            <input name="name"
                   value="{{ $student->name }}"
                   class="w-full border rounded px-3 py-2">
        </div>

        <div class="mb-4">
            <label class="text-sm">Kelas</label>
            <input name="class"
                   value="{{ $student->class }}"
                   class="w-full border rounded px-3 py-2">
        </div>

        <div class="flex gap-3">
            <button class="bg-indigo-600 text-white px-4 py-2 rounded">
                Simpan
            </button>

            <a href="{{ route('school.students.index') }}"
               class="px-4 py-2 border rounded">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
