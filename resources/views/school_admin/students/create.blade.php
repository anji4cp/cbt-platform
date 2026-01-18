@extends('layouts.school_admin')

@section('content')
<h1 class="text-xl font-semibold mb-6 text-center">Tambah Siswa</h1>
<div class="flex justify-center">
<div class="w-full max-w-xl bg-white rounded-xl shadow p-6">
    @if ($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 border border-red-200 p-4 text-sm text-red-700">
            <div class="font-semibold mb-1">Gagal menambahkan siswa:</div>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <form method="POST" action="{{ route('school.students.store') }}" class="space-y-4">
        @csrf

        <!-- NAMA -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Nama Siswa
            </label>
            <input
                name="name"
                required
                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-indigo-200"
                placeholder="Nama lengkap siswa">
        </div>

        <!-- USERNAME -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Username
            </label>
            <input
                name="username"
                required
                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-indigo-200"
                placeholder="Username login siswa">
        </div>

        <!-- KELAS -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Kelas
            </label>
            <input
                name="class"
                required
                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-indigo-200"
                placeholder="Contoh: X IPA 1">
        </div>

        <!-- PASSWORD -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Password
            </label>
            <input
                name="password"
                required
                type="password"
                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-indigo-200"
                placeholder="Masukan Password">
        </div>

        <!-- BUTTON -->
        <div class="flex justify-center gap-3 pt-4">
            <button
                class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">
                Simpan
            </button>

            <a href="{{ route('school.students.index') }}"
               class="px-6 py-2 rounded-lg border border-gray-300 hover:bg-gray-100">
                Batal
            </a>
        </div>
    </form>
</div>
</div>
@endsection
