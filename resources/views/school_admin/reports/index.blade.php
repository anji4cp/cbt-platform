@extends('layouts.school_admin')

@section('content')

{{-- ================= HEADER ================= --}}
<div class="mb-6">
    <h1 class="text-xl font-semibold">Laporan Nilai</h1>
    <p class="text-sm text-slate-500">
        Rekap hasil ujian siswa.
    </p>
</div>

{{-- ================= FILTER CARD ================= --}}
<div class="bg-white rounded-xl shadow-sm p-5 mb-6">

    <form method="GET"
          class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- UJIAN --}}
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">
                Ujian
            </label>
            <select name="exam_id"
                    class="w-full border rounded-lg px-3 py-2 text-sm">
                <option value="">Semua Ujian</option>
                @foreach($exams as $exam)
                    <option value="{{ $exam->id }}"
                        {{ $examId == $exam->id ? 'selected' : '' }}>
                        {{ $exam->subject }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- KELAS --}}
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">
                Kelas
            </label>
            <select name="class"
                    class="w-full border rounded-lg px-3 py-2 text-sm">
                <option value="">Semua Kelas</option>
                @foreach($classes as $c)
                    <option value="{{ $c }}"
                        {{ $class == $c ? 'selected' : '' }}>
                        {{ $c }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- BUTTON FILTER --}}
        <div class="flex items-end">
            <button
                class="w-full bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm
                       hover:bg-indigo-700">
                Filter
            </button>
        </div>

        {{-- EXPORT --}}
        <div class="flex items-end">
            <a href="{{ route('school.reports.export', request()->query()) }}"
               class="w-full text-center bg-green-600 text-white px-4 py-2 rounded-lg text-sm
                      hover:bg-green-700">
                Export Excel
            </a>
        </div>

    </form>
</div>

{{-- ================= TABLE ================= --}}
<div class="bg-white rounded-xl shadow-sm overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-slate-100 text-slate-600">
            <tr>
                <th class="px-4 py-3 text-left">Nama</th>
                <th class="px-4 py-3 text-left">Kelas</th>
                <th class="px-4 py-3 text-left">Ujian</th>
                <th class="px-4 py-3 text-center">Nilai</th>
                <th class="px-4 py-3 text-left hidden sm:table-cell">Waktu</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sessions as $s)
                <tr class="border-t hover:bg-slate-50">
                    <td class="px-4 py-3">{{ $s->student->name }}</td>
                    <td class="px-4 py-3">{{ $s->student->class }}</td>
                    <td class="px-4 py-3">{{ $s->exam->subject }}</td>
                    <td class="px-4 py-3 text-center font-semibold">
                        {{ $s->score }}
                    </td>
                    <td class="px-4 py-3 hidden sm:table-cell text-sm text-slate-500">
                        {{ $s->submitted_at->format('d-m-Y H:i') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-6 text-slate-400">
                        Tidak ada data
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
