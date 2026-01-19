@extends('layouts.school_admin')

@section('content')

{{-- ================= SERVER ID CARD ================= --}}
<div
    class="rounded-2xl mb-6 p-5 flex flex-col sm:flex-row gap-4 items-center justify-between text-white"
    style="background: linear-gradient(135deg, {{ auth()->user()->school->theme_color ?? '#2563eb' }}, #1e40af);"
>
    {{-- LOGO --}}
    <div class="flex items-center justify-center">
        @if(auth()->user()->school->logo)
            <img
                src="{{ asset('storage/' . auth()->user()->school->logo) }}"
                class="w-16 h-16 object-cover rounded-xl bg-white p-2"
                alt="Logo Sekolah">
        @else
            <div class="w-16 h-16 rounded-xl bg-white/30 flex items-center justify-center text-xs font-semibold">
                LOGO
            </div>
        @endif
    </div>

    {{-- INFO --}}
    <div class="flex-1 text-center sm:text-left">
        @if($school->status === 'suspend')
            <div class="text-lg font-bold text-red-200">
                🚫 SEKOLAH DISUSPEND
            </div>
            <div class="text-sm opacity-80 mt-1">
                Akses login siswa & admin dinonaktifkan
            </div>
        @else
            <div class="text-sm opacity-80">
                Server ID Sekolah
            </div>
            <div class="text-2xl font-bold tracking-widest">
                {{ $serverId }}
            </div>
            <div class="text-xs opacity-75">
                Bagikan ke siswa untuk login pertama
            </div>
        @endif
    </div>

    {{-- COPY --}}
    @if($school->status !== 'suspend')
        <button
            onclick="navigator.clipboard.writeText('{{ $serverId }}')"
            class="bg-white text-indigo-700 font-semibold px-4 py-2 rounded-xl hover:bg-indigo-50 transition">
            Copy
        </button>
    @endif
</div>

{{-- ================= STAT CARDS ================= --}}
<h2 class="text-lg font-semibold mb-4">Dashboard Admin Sekolah</h2>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-xl p-4 shadow-sm">
        <div class="text-sm text-gray-500">Total Siswa</div>
        <div class="text-2xl font-bold">{{ $totalStudents }}</div>
    </div>

    <div class="bg-white rounded-xl p-4 shadow-sm">
        <div class="text-sm text-gray-500">Siswa Aktif</div>
        <div class="text-2xl font-bold">{{ $activeStudents }}</div>
    </div>

    <div class="bg-white rounded-xl p-4 shadow-sm">
        <div class="text-sm text-gray-500">Total Ujian</div>
        <div class="text-2xl font-bold">{{ $totalExams }}</div>
    </div>

    <div class="bg-white rounded-xl p-4 shadow-sm">
        <div class="text-sm text-gray-500">Ujian Aktif</div>
        <div class="text-2xl font-bold">{{ $activeExams }}</div>
    </div>
</div>

{{-- ================= FILTER KELAS ================= --}}
<h3 class="text-lg font-semibold mb-3">Monitoring Siswa</h3>

<div class="flex flex-wrap gap-2 mb-4">
    <a href="{{ route('school.dashboard') }}"
       class="px-4 py-1.5 rounded-full text-sm
       {{ !$selectedClass ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700' }}">
        Semua
    </a>

    @foreach($classes as $class)
        <a href="{{ route('school.dashboard', ['class' => $class]) }}"
           class="px-4 py-1.5 rounded-full text-sm
           {{ $selectedClass === $class ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700' }}">
            {{ $class }}
        </a>
    @endforeach
</div>

{{-- ================= TABEL / MOBILE LIST ================= --}}
<div class="bg-white rounded-xl shadow-sm overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-3 text-left">Nama</th>
                <th class="p-3 text-left">Kelas</th>
                <th class="p-3 text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $s)
                <tr class="border-t">
                    <td class="p-3">{{ $s['name'] }}</td>
                    <td class="p-3">{{ $s['class'] }}</td>
                    <td class="p-3 text-center">
                        @if($s['status'] === 'Sedang Ujian')
                            <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-800 text-xs font-semibold">
                                Sedang Ujian
                            </span>
                        @elseif($s['status'] === 'Selesai')
                            <span class="px-3 py-1 rounded-full bg-green-100 text-green-800 text-xs font-semibold">
                                Selesai
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-xs font-semibold">
                                Belum Ujian
                            </span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="p-6 text-center text-gray-500">
                        Tidak ada siswa
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
