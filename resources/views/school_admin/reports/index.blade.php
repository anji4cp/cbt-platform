@extends('layouts.school_admin')

@section('content')

<div class="bg-white rounded-xl shadow p-6">

    <h2 class="text-lg font-semibold mb-4">Laporan Nilai</h2>

    {{-- FILTER --}}
    <form method="GET" class="flex gap-4 mb-4 flex-wrap">

        <select name="exam_id" class="border rounded px-3 py-2">
            <option value="">Semua Ujian</option>
            @foreach($exams as $exam)
                <option value="{{ $exam->id }}"
                    {{ $examId == $exam->id ? 'selected' : '' }}>
                    {{ $exam->subject }}
                </option>
            @endforeach
        </select>

        <select name="class" class="border rounded px-3 py-2">
            <option value="">Semua Kelas</option>
            @foreach($classes as $c)
                <option value="{{ $c }}"
                    {{ $class == $c ? 'selected' : '' }}>
                    {{ $c }}
                </option>
            @endforeach
        </select>

        <button class="bg-indigo-600 text-white px-4 py-2 rounded">
            Filter
        </button>

        <a href="{{ route('school.reports.export', request()->query()) }}"
        class="bg-green-600 text-white px-4 py-2 rounded">
            Export Excel
        </a>
    </form>

    {{-- TABLE --}}
    <div class="overflow-x-auto">
        <table class="w-full border-collapse">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border">Nama</th>
                    <th class="p-2 border">Kelas</th>
                    <th class="p-2 border">Ujian</th>
                    <th class="p-2 border">Nilai</th>
                    <th class="p-2 border">Waktu</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sessions as $s)
                <tr class="text-center">
                    <td class="border p-2">{{ $s->student->name }}</td>
                    <td class="border p-2">{{ $s->student->class }}</td>
                    <td class="border p-2">{{ $s->exam->subject }}</td>
                    <td class="border p-2 font-semibold">{{ $s->score }}</td>
                    <td class="border p-2 text-sm">
                        {{ $s->submitted_at->format('d-m-Y H:i') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center p-4 text-gray-500">
                        Tidak ada data
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection
