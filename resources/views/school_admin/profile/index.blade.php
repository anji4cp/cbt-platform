@extends('layouts.school_admin')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-2xl shadow p-6">
    <div class="flex items-center gap-4 mb-6">
        @if($school->logo)
            <img src="{{ asset('storage/'.$school->logo) }}"
                 class="w-20 h-20 rounded-lg object-contain bg-gray-100">
        @else
            <div class="w-20 h-20 rounded-lg bg-gray-200 flex items-center justify-center">
                LOGO
            </div>
        @endif

        <div>
            <h2 class="text-xl font-semibold">{{ $school->name }}</h2>
            <p class="text-sm text-gray-500">Server ID: {{ $school->school_id }}</p>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4 text-sm">
        <div>
            <span class="text-gray-500">Status</span><br>
            <strong>{{ strtoupper($school->status) }}</strong>
        </div>

        <div>
            <span class="text-gray-500">Kontak</span><br>
            <strong>{{ $school->contact ?? '-' }}</strong>
        </div>

        <div>
            <span class="text-gray-500">Warna Tema</span><br>
            <span class="inline-block px-3 py-1 rounded text-white"
                  style="background: {{ $school->theme_color }}">
                {{ $school->theme_color }}
            </span>
        </div>
    </div>

    <div class="mt-6 text-right">
        <a href="{{ route('school.profile.edit') }}"
           class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
            Edit Profil
        </a>
    </div>
    <hr class="my-8">

</div>
@endsection
