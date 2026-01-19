@extends('layouts.public')

@php
    $title = 'CBT Sekolah';
    $heading = 'CBT Sekolah';
    $subheading = 'Masukkan ID Sekolah untuk melanjutkan';
@endphp

@section('content')

{{-- ERROR --}}
@if ($errors->any())
    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
        {{ $errors->first() }}
    </div>
@endif

<form method="POST"
      action="{{ route('student.server.set') }}"
      class="space-y-6">
    @csrf

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1 text-center">
            ID Sekolah / Server ID
        </label>
        <input type="text"
               name="server_id"
               placeholder="Minta ke Pengawas atau Admin"
               required
               autofocus
               class="w-full rounded-lg border px-3 py-3 text-center focus:ring-2 focus:ring-indigo-200">
    </div>

    <button
        class="w-full bg-indigo-600 text-white py-3 rounded-lg font-semibold hover:bg-indigo-700 transition">
        Lanjutkan
    </button>
</form>

<div class="mt-6 text-center text-xs text-gray-400">
    © {{ date('Y') }} CBT Platform
</div>

@endsection
