@extends('layouts.school_admin')

@section('content')
{{-- SERVER ID CARD --}}
<div style="
    background: linear-gradient(135deg, {{ auth()->user()->school->theme_color ?? '#2563eb' }}, #1e40af);
    color:#fff;
    padding:18px 22px;
    border-radius:16px;
    margin-bottom:24px;
    display:flex;
    align-items:stretch;
    gap:20px;
">

    {{-- LOGO KIRI (SETINGGI CARD) --}}
    <div style="
        width:80px;
        display:flex;
        align-items:center;
        justify-content:center;
    ">
        @if(auth()->user()->school->logo)
            <img
                src="{{ asset('storage/' . auth()->user()->school->logo) }}"
                style="
                    width:64px;
                    height:64px;
                    object-fit:cover;
                    border-radius:14px;
                    background:#fff;
                    padding:6px;
                "
                alt="Logo Sekolah">
        @else
            <div style="
                width:64px;
                height:64px;
                border-radius:14px;
                background:rgba(255,255,255,.25);
                display:flex;
                align-items:center;
                justify-content:center;
                font-size:12px;
                font-weight:600;
            ">
                LOGO
            </div>
        @endif
    </div>

    {{-- KONTEN TENGAH --}}
<div style="flex:1">

    @if($school->status === 'suspend')

        <div style="font-size:18px;font-weight:700;color:#fee2e2">
            🚫 SEKOLAH DISUSPEND
        </div>

        <div style="font-size:13px;opacity:.85;margin-top:6px;color:#fecaca">
            Akses login siswa dan admin dinonaktifkan
        </div>

    @else

        <div style="font-size:13px;opacity:.85">
            Server ID Sekolah
        </div>

        <div style="font-size:26px;font-weight:700;letter-spacing:1px">
            {{ $serverId }}
        </div>

        <div style="font-size:12px;opacity:.8">
            Bagikan ke siswa untuk login pertama
        </div>

    @endif
</div>

{{-- TOMBOL COPY --}}
@if($school->status !== 'suspend')
<div style="display:flex;align-items:center">
    <button
        onclick="navigator.clipboard.writeText('{{ $serverId }}')"
        style="
            background:#fff;
            color:#1e40af;
            border:none;
            padding:12px 18px;
            border-radius:12px;
            font-weight:600;
            cursor:pointer;
            height:fit-content;
        ">
        Copy
    </button>
</div>
@endif


</div>

<h2>Dashboard Admin Sekolah</h2>

<div style="display:grid; grid-template-columns:repeat(4,1fr); gap:15px; margin-bottom:30px">

    <div style="background:#fff; padding:15px; border-radius:8px">
        <h4>Total Siswa</h4>
        <strong>{{ $totalStudents }}</strong>
    </div>

    <div style="background:#fff; padding:15px; border-radius:8px">
        <h4>Siswa Aktif</h4>
        <strong>{{ $activeStudents }}</strong>
    </div>

    <div style="background:#fff; padding:15px; border-radius:8px">
        <h4>Total Ujian</h4>
        <strong>{{ $totalExams }}</strong>
    </div>

    <div style="background:#fff; padding:15px; border-radius:8px">
        <h4>Ujian Aktif</h4>
        <strong>{{ $activeExams }}</strong>
    </div>

</div>

<!-- <div style="background:#fff; padding:20px; border-radius:8px">
    <h3>Status Ujian</h3>

    @if($runningSessions > 0)
        <p style="color:green">
            🟢 {{ $runningSessions }} siswa sedang mengerjakan ujian
        </p>
    @else
        <p style="color:gray">
            ⚪ Tidak ada ujian yang sedang berlangsung
        </p>
    @endif
</div> -->

<h3 class="text-lg font-semibold mb-4">Monitoring Siswa</h3>

<div class="flex gap-2 flex-wrap mb-4">
    <a href="{{ route('school.dashboard') }}"
       class="px-4 py-1 rounded {{ !$selectedClass ? 'bg-indigo-600 text-white' : 'bg-gray-200' }}">
        Semua
    </a>

    @foreach($classes as $class)
        <a href="{{ route('school.dashboard', ['class' => $class]) }}"
           class="px-4 py-1 rounded
           {{ $selectedClass === $class ? 'bg-indigo-600 text-white' : 'bg-gray-200' }}">
            {{ $class }}
        </a>
    @endforeach
</div>

<table class="w-full border bg-white rounded shadow">
    <thead class="bg-gray-100">
        <tr>
            <th class="p-2 border">Nama</th>
            <th class="p-2 border">Kelas</th>
            <th class="p-2 border">Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($students as $s)
            <tr class="text-center">
                <td class="border p-2">{{ $s['name'] }}</td>
                <td class="border p-2">{{ $s['class'] }}</td>
                <td class="border p-2">
                    @if($s['status'] === 'Sedang Ujian')
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded">
                            Sedang Ujian
                        </span>
                    @elseif($s['status'] === 'Selesai')
                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded">
                            Selesai
                        </span>
                    @else
                        <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded">
                            Belum Ujian
                        </span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="p-4 text-center text-gray-500">
                    Tidak ada siswa
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

@endsection
