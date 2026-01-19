@extends('layouts.school_admin')

@section('content')

{{-- ================= HEADER ================= --}}
<div class="mb-6">
    <h1 class="text-xl font-semibold">Profil Sekolah</h1>
    <p class="text-sm text-slate-500">
        Informasi dasar sekolah dan pengaturan tampilan.
    </p>
</div>

{{-- ================= PROFILE CARD ================= --}}
<div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-sm p-6">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center gap-4 mb-6">
        @if($school->logo)
            <img
                src="{{ asset('storage/'.$school->logo) }}"
                alt="Logo Sekolah"
                class="w-20 h-20 rounded-xl object-contain bg-slate-100 p-2">
        @else
            <div
                class="w-20 h-20 rounded-xl bg-slate-200 flex items-center justify-center
                       text-xs font-semibold text-slate-600">
                LOGO
            </div>
        @endif

        <div>
            <h2 class="text-lg font-semibold text-slate-800">
                {{ $school->name }}
            </h2>
            <p class="text-sm text-slate-500">
                Server ID: <span class="font-medium">{{ $school->school_id }}</span>
            </p>
        </div>
    </div>

    {{-- INFO GRID --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">

        {{-- STATUS --}}
        <div>
            <div class="text-slate-500 mb-1">Status</div>
            <span
                class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                {{ $school->status === 'active'
                    ? 'bg-green-100 text-green-700'
                    : ($school->status === 'suspend'
                        ? 'bg-red-100 text-red-700'
                        : 'bg-yellow-100 text-yellow-700') }}">
                {{ strtoupper($school->status) }}
            </span>
        </div>

        {{-- KONTAK --}}
        <div>
            <div class="text-slate-500 mb-1">Kontak</div>
            <div class="font-medium text-slate-700">
                {{ $school->contact ?? '-' }}
            </div>
        </div>

        {{-- TEMA --}}
        <div>
            <div class="text-slate-500 mb-1">Warna Tema</div>
            <span
                class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-medium text-white"
                style="background: {{ $school->theme_color }}">
                <span class="w-3 h-3 rounded-full bg-white/70"></span>
                {{ $school->theme_color }}
            </span>
        </div>

    </div>

    {{-- ACTION --}}
    <div class="mt-8 flex justify-end">
        <a href="{{ route('school.profile.edit') }}"
           class="bg-indigo-600 text-white px-5 py-2 rounded-lg text-sm
                  hover:bg-indigo-700">
            Edit Profil
        </a>
    </div>

</div>

@endsection
