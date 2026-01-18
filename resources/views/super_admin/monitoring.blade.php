@extends('layouts.super_admin')

@section('content')

<h1 class="text-2xl font-semibold mb-6">Monitoring Global Sekolah</h1>

<div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">

@foreach($schools as $school)
<div class="bg-white rounded-xl shadow-sm border p-5">

    {{-- HEADER --}}
    <div class="flex justify-between items-start mb-4">
        <div>
            <h3 class="font-semibold text-lg text-gray-800">
                {{ $school['name'] }}
            </h3>
            <div class="text-xs text-gray-500 mt-1">
                Monitoring Sekolah
            </div>
        </div>

        {{-- STATUS --}}
        <span class="px-3 py-1 rounded-full text-xs font-semibold
            {{ $school['status'] === 'active' ? 'bg-green-100 text-green-700' :
               ($school['status'] === 'trial' ? 'bg-yellow-100 text-yellow-700' :
               ($school['status'] === 'suspend' ? 'bg-gray-200 text-gray-700' :
               'bg-red-100 text-red-700')) }}">
            {{ strtoupper($school['status']) }}
        </span>
    </div>

    {{-- STAT GRID --}}
    <div class="grid grid-cols-2 gap-4 text-sm">

        <div class="bg-slate-50 rounded-lg p-3">
            <div class="text-gray-500 text-xs">Total Siswa</div>
            <div class="text-xl font-bold text-indigo-600">
                {{ $school['total_students'] }}
            </div>
        </div>

        <div class="bg-slate-50 rounded-lg p-3">
            <div class="text-gray-500 text-xs">Siswa Aktif</div>
            <div class="text-xl font-bold text-green-600">
                {{ $school['active_students'] }}
            </div>
        </div>

        <div class="bg-slate-50 rounded-lg p-3">
            <div class="text-gray-500 text-xs">Total Ujian</div>
            <div class="text-xl font-bold text-blue-600">
                {{ $school['total_exams'] }}
            </div>
        </div>

        <div class="bg-slate-50 rounded-lg p-3">
            <div class="text-gray-500 text-xs">Ujian Aktif</div>
            <div class="text-xl font-bold text-orange-500">
                {{ $school['active_exams'] }}
            </div>
        </div>

    </div>

    {{-- FOOTER --}}
    <div class="mt-4 pt-3 border-t text-sm flex justify-between items-center">

        <div class="text-gray-500">
            Sedang Ujian
        </div>

        <div class="text-lg font-bold
            {{ $school['running_sessions'] > 0 ? 'text-red-600' : 'text-gray-400' }}">
            {{ $school['running_sessions'] }}
        </div>

    </div>

</div>
@endforeach

</div>

@if(empty($schools))
<div class="text-center text-gray-400 py-12">
    Belum ada data sekolah
</div>
@endif

@endsection
