@extends('layouts.super_admin')

@section('content')

<h1 class="text-2xl font-semibold mb-6 text-center">Buat Admin Sekolah</h1>
<div class="flex justify-center p-3">
<div class="w-full max-w-xl bg-white rounded-xl shadow p-6">

    {{-- INFO SEKOLAH --}}
    <div class="mb-6">
        <div class="text-sm text-gray-500">Sekolah</div>
        <div class="text-lg font-semibold text-gray-800">
            {{ $school->name }}
        </div>
    </div>

    {{-- ERROR --}}
    @if ($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg p-3">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- FORM --}}
    <form method="POST" action="{{ route('superadmin.schools.admin.store', $school) }}" class="space-y-4">
        @csrf

        {{-- NAMA --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Nama Admin
            </label>
            <input
                type="text"
                name="name"
                value="{{ old('name') }}"
                required
                class="w-full border rounded-lg px-3 py-2 text-sm focus:ring focus:ring-indigo-200 focus:border-indigo-500"
                placeholder="Contoh: Budi Santoso">
        </div>

        {{-- EMAIL --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Email Admin
            </label>
            <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                class="w-full border rounded-lg px-3 py-2 text-sm focus:ring focus:ring-indigo-200 focus:border-indigo-500"
                placeholder="admin@sekolah.sch.id">
        </div>

        {{-- PASSWORD --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Password
            </label>
            <input
                type="password"
                name="password"
                required
                class="w-full border rounded-lg px-3 py-2 text-sm focus:ring focus:ring-indigo-200 focus:border-indigo-500"
                placeholder="Minimal 8 karakter">
        </div>

        {{-- ACTION --}}
        <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('superadmin.schools.index') }}"
               class="px-4 py-2 text-sm rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-50">
                Batal
            </a>

            <button
                type="submit"
                class="px-5 py-2 text-sm rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">
                Buat Admin
            </button>
        </div>
    </form>
</div>
</div>

@endsection
