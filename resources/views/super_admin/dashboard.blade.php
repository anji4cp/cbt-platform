@extends('layouts.super_admin')

@section('content')

{{-- ================= HEADER ================= --}}
<div class="mb-6">
    <h1 class="text-2xl font-semibold">Dashboard Super Admin</h1>
    <p class="text-sm text-slate-500">
        Ringkasan status sekolah dan aktivitas terbaru
    </p>
</div>

{{-- ================= SUMMARY ================= --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

    <div class="bg-white rounded-xl shadow-sm p-5">
        <div class="text-sm text-gray-500">Total Sekolah</div>
        <div class="text-3xl font-bold text-indigo-600">
            {{ $totalSchools }}
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-5">
        <div class="text-sm text-gray-500">Sekolah Aktif</div>
        <div class="text-3xl font-bold text-green-600">
            {{ $activeSchools ?? 0 }}
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-5">
        <div class="text-sm text-gray-500">Sekolah Trial</div>
        <div class="text-3xl font-bold text-yellow-500">
            {{ $trialSchools ?? 0 }}
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-5">
        <div class="text-sm text-gray-500">Sekolah Expired</div>
        <div class="text-3xl font-bold text-red-600">
            {{ $expiredSchools ?? 0 }}
        </div>
    </div>

</div>

{{-- ================= RECENT SCHOOLS ================= --}}
<div class="bg-white rounded-xl shadow-sm p-6">

    <div class="flex items-center justify-between mb-4">
        <h3 class="font-semibold">Sekolah Terbaru</h3>
        <span class="text-xs text-gray-400">
            Menampilkan {{ count($recentSchools ?? []) }} data
        </span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm min-w-[720px]">
            <thead class="bg-slate-100 text-slate-600">
                <tr>
                    <th class="px-4 py-3 text-left">Nama Sekolah</th>
                    <th class="px-4 py-3 text-center">Server ID</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-center">Dibuat</th>
                    <th class="px-4 py-3 text-center">Expired</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentSchools ?? [] as $school)
                <tr class="border-t hover:bg-slate-50">

                    <td class="px-4 py-3 font-medium">
                        {{ $school->name }}
                    </td>

                    <td class="px-4 py-3 text-center font-mono">
                        {{ $school->school_id }}
                    </td>

                    <td class="px-4 py-3 text-center">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                            {{ $school->status === 'active'
                                ? 'bg-green-100 text-green-700'
                                : ($school->status === 'trial'
                                    ? 'bg-yellow-100 text-yellow-700'
                                    : 'bg-red-100 text-red-700') }}">
                            {{ strtoupper($school->status) }}
                        </span>
                    </td>

                    <td class="px-4 py-3 text-center">
                        {{ $school->created_at->format('d/m/Y') }}
                    </td>

                    <td class="px-4 py-3 text-center text-sm">
                        @if($school->expired_at)
                            @php
                                $expiredDate = \Carbon\Carbon::parse($school->expired_at)->startOfDay();
                                $today       = now()->startOfDay();
                                $daysLeft    = $today->diffInDays($expiredDate, false);
                            @endphp

                            <div>{{ $expiredDate->format('d/m/Y') }}</div>
                            <div class="text-xs text-gray-400">
                                @if($daysLeft > 0)
                                    {{ $daysLeft }} hari lagi
                                @elseif($daysLeft === 0)
                                    Hari ini
                                @else
                                    Expired
                                @endif
                            </div>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-6 text-gray-400">
                        Belum ada data sekolah
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection
