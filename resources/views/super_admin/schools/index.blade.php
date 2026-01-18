@extends('layouts.super_admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold text-gray-800">
        Daftar Sekolah
    </h1>

    <a href="{{ route('schools.create') }}"
       class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow">
        + Tambah Sekolah
    </a>
</div>

@if(session('success'))
    <div class="mb-6 flex items-start gap-3 rounded-lg border border-green-200 bg-green-50 p-4 text-green-800">
        <div class="text-xl">✅</div>
        <div class="text-sm font-medium">
            {{ session('success') }}
        </div>
    </div>
@endif

<div class="bg-white rounded-xl shadow overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-slate-100 text-slate-600">
            <tr>
                <th class="px-4 py-3 text-left">Nama Sekolah</th>
                <th class="px-4 py-3 text-center">School ID</th>
                <th class="px-4 py-3 text-center">Email</th>
                <th class="px-4 py-3 text-center">Status</th>
                <th class="px-4 py-2">Expired at</th>
                <th class="px-4 py-3 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($schools as $school)
            <tr class="border-t hover:bg-slate-50">
                <td class="px-4 py-3 font-medium text-gray-800">
                    {{ $school->name }}
                </td>

                <td class="px-4 py-3 text-center font-mono text-indigo-600">
                    {{ $school->school_id }}
                </td>

                <td class="px-4 py-3 text-center text-sm text-gray-700">
                    @if($school->admins->count())
                        @foreach($school->admins as $admin)
                            <div class="font-medium text-gray-600">
                                {{ $admin->email }}
                            </div>
                        @endforeach
                    @else
                        <span class="text-gray-400 italic">Belum ada admin</span>
                    @endif
                </td>


                <td class="px-4 py-3 text-center">
                    <span class="px-2 py-1 rounded text-xs font-semibold
                        {{ $school->status === 'active' ? 'bg-green-100 text-green-700' :
                           ($school->status === 'trial' ? 'bg-yellow-100 text-yellow-700' :
                           'bg-red-100 text-red-700') }}">
                        {{ strtoupper($school->status) }}
                    </span>
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

                <td class="px-4 py-2 text-center">
                    <div class="flex justify-center gap-2">

                        {{-- EDIT --}}
                        <a href="{{ route('schools.edit', $school) }}"
                        class="px-3 py-1 text-xs rounded bg-blue-100 text-blue-700 hover:bg-blue-200">
                            Edit
                        </a>

                        {{-- BUAT ADMIN --}}
                        <a href="{{ route('schools.admin.create', $school) }}"
                        class="px-3 py-1 text-xs rounded bg-indigo-100 text-indigo-700 hover:bg-indigo-200">
                            Admin
                        </a>

                        {{-- HAPUS --}}
                        <form method="POST"
                            action="{{ route('schools.destroy', $school) }}"
                            onsubmit="return confirm('⚠️ Yakin hapus sekolah ini?\nSemua data akan TERHAPUS permanen!')">
                            @csrf
                            @method('DELETE')

                            <button
                                class="px-3 py-1 text-xs rounded bg-red-100 text-red-700 hover:bg-red-200">
                                Hapus
                            </button>
                        </form>

                    </div>
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
