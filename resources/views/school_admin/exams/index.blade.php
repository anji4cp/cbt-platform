@extends('layouts.school_admin')

@section('content')

{{-- ================= HEADER ================= --}}
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
    <div>
        <h1 class="text-xl font-semibold">Manajemen Ujian</h1>
        <p class="text-sm text-slate-500">
            Kelola ujian, token, dan status aktif.
        </p>
    </div>

    <a href="{{ route('school.exams.create') }}"
       class="bg-indigo-600 text-white px-5 py-2 rounded-lg text-sm
              hover:bg-indigo-700 text-center">
        + Buat Ujian
    </a>
</div>

{{-- ================= FLASH ================= --}}
@if(session('success'))
    <div class="mb-4 flex items-start gap-3 rounded-lg border border-green-200 bg-green-50 p-4 text-green-800">
        <span class="text-xl">✅</span>
        <span class="text-sm font-medium">{{ session('success') }}</span>
    </div>
@endif

@if(session('error'))
    <div class="mb-4 flex items-start gap-3 rounded-lg border border-red-200 bg-red-50 p-4 text-red-800">
        <span class="text-xl">⚠️</span>
        <span class="text-sm font-medium">{{ session('error') }}</span>
    </div>
@endif

{{-- ================= TABLE ================= --}}
<div class="bg-white rounded-xl shadow-sm overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-slate-100 text-slate-600">
            <tr>
                <th class="px-4 py-3 text-left">Mata Pelajaran</th>
                <th class="px-4 py-3 text-left hidden sm:table-cell">Token</th>
                <th class="px-4 py-3 text-center">Status</th>
                <th class="px-4 py-3 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($exams as $exam)
                <tr class="border-t hover:bg-slate-50">
                    <td class="px-4 py-3 font-medium text-slate-800">
                        {{ $exam->subject }}
                    </td>

                    <td class="px-4 py-3 font-mono hidden sm:table-cell">
                        {{ $exam->token ?? '-' }}
                    </td>

                    <td class="px-4 py-3 text-center">
                        @if($exam->is_active)
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">
                                Aktif
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-700">
                                Nonaktif
                            </span>
                        @endif
                    </td>

                    <td class="px-4 py-3 text-center">
                        <div class="flex flex-wrap justify-center gap-2">

                            {{-- EDIT --}}
                            <a href="{{ route('school.exams.edit', $exam) }}"
                               class="px-3 py-1 text-xs rounded bg-blue-100 text-blue-700
                                      hover:bg-blue-200">
                                Edit
                            </a>

                            {{-- TOGGLE --}}
                            <form method="POST"
                                  action="{{ route('school.exams.toggle', $exam) }}">
                                @csrf
                                @method('PATCH')
                                <button
                                    class="px-3 py-1 text-xs rounded
                                    {{ $exam->is_active
                                        ? 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200'
                                        : 'bg-green-100 text-green-700 hover:bg-green-200' }}">
                                    {{ $exam->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>

                            {{-- DELETE --}}
                            <form method="POST"
                                  action="{{ route('school.exams.destroy', $exam) }}"
                                  onsubmit="return confirm('Hapus ujian ini?')">
                                @csrf
                                @method('DELETE')
                                <button
                                    class="px-3 py-1 text-xs rounded bg-red-100 text-red-700
                                           hover:bg-red-200">
                                    Hapus
                                </button>
                            </form>

                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center py-6 text-slate-400">
                        Belum ada ujian
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
