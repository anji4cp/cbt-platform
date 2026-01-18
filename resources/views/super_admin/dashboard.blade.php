@extends('layouts.super_admin')

@section('content')

<h1 class="text-2xl font-semibold mb-6">Dashboard Super Admin</h1>

{{-- SUMMARY CARDS --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">

    <div class="bg-white rounded-xl shadow p-5">
        <div class="text-sm text-gray-500">Total Sekolah</div>
        <div class="text-3xl font-bold text-indigo-600">
            {{ $totalSchools }}
        </div>
    </div>

    <div class="bg-white rounded-xl shadow p-5">
        <div class="text-sm text-gray-500">Sekolah Aktif</div>
        <div class="text-3xl font-bold text-green-600">
            {{ $activeSchools ?? 0 }}
        </div>
    </div>

    <div class="bg-white rounded-xl shadow p-5">
        <div class="text-sm text-gray-500">Sekolah Trial</div>
        <div class="text-3xl font-bold text-yellow-500">
            {{ $trialSchools ?? 0 }}
        </div>
    </div>

    <div class="bg-white rounded-xl shadow p-5">
        <div class="text-sm text-gray-500">Sekolah Expired</div>
        <div class="text-3xl font-bold text-red-600">
            {{ $expiredSchools ?? 0 }}
        </div>
    </div>

</div>

{{-- RECENT SCHOOL ACTIVITY --}}
<div class="bg-white rounded-xl shadow p-6">
    <h3 class="font-semibold mb-4">Sekolah Terbaru</h3>

    <table class="w-full text-sm">
        <thead class="bg-slate-100 text-slate-600">
            <tr>
                <th class="px-4 py-2 text-left">Nama Sekolah</th>
                <th class="px-4 py-2">Server ID</th>
                <th class="px-4 py-2">Status</th>
                <th class="px-4 py-2">Dibuat</th>
                <th class="px-4 py-2">Expired at</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentSchools ?? [] as $school)
            <tr class="border-t">
                <td class="px-4 py-2">{{ $school->name }}</td>
                <td class="px-4 py-2 text-center font-mono">
                    {{ $school->school_id }}
                </td>
                <td class="px-4 py-2 text-center">
                    <span class="px-2 py-1 rounded text-xs
                        {{ $school->status === 'active' ? 'bg-green-100 text-green-700' :
                            ($school->status === 'trial' ? 'bg-yellow-100 text-yellow-700' :
                           'bg-red-100 text-red-700') }}">
                        {{ strtoupper($school->status) }}
                    </span>
                </td>
                <td class="px-4 py-2 text-center">
                    {{ $school->created_at->format('d/m/Y') }}
                </td>
                <td class="px-4 py-2 text-center text-sm">
                    @if($school->expired_at)
                        @php
                            $expiredDate = \Carbon\Carbon::parse($school->expired_at)->startOfDay();
                            $today       = now()->startOfDay();
                            $daysLeft    = $today->diffInDays($expiredDate, false);
                        @endphp

                        {{ $expiredDate->format('d/m/Y') }}

                        <div class="text-xs text-gray-400">
                            @if($daysLeft > 0)
                                {{ $daysLeft }} hari lagi
                            @elseif($daysLeft === 0)
                                Hari ini
                            @else
                                Expired
                            @endif
                        </div>
                    @endif

                </td>

            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center py-6 text-gray-400">
                    Belum ada data sekolah
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
