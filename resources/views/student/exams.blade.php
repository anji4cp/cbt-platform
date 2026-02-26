@extends('layouts.student')

@section('content')

<div class="exam-container max-w-6xl mx-auto w-full">

    {{-- FLASH --}}
    @if(session('success'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-4 text-green-800 text-sm">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-4 text-red-800 text-sm">
            ⚠️ {{ session('error') }}
        </div>
    @endif

    <div class="exam-card">
        <div class="exam-header">
            <h2>📘 Daftar Ujian Aktif</h2>
            <div class="text-xs text-gray-500">
                {{ $exams->count() }} ujian tersedia
            </div>
        </div>

        {{-- ===== DESKTOP TABLE ===== --}}
        <div class="exam-table-wrapper">
            <table class="exam-table">
                <thead>
                    <tr>
                        <th>Mata Pelajaran</th>
                        <th>Durasi</th>
                        <th style="width:160px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($exams as $exam)
                        <tr>
                            <td><strong>{{ $exam->subject }}</strong></td>
                            <td>
                                <span class="badge">{{ $exam->duration_minutes }} menit</span>
                            </td>
                            <td>
                                <a href="{{ route('student.exam.start', $exam->id) }}"
                                   class="btn-start">
                                    Mulai Ujian
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-gray-500 py-8">
                                Tidak ada ujian tersedia
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ===== MOBILE CARD ===== --}}
        <div class="exam-mobile">
            @forelse($exams as $exam)
                <div class="exam-item">
                    <h3>{{ $exam->subject }}</h3>
                    <div class="meta">
                        Durasi: <strong>{{ $exam->duration_minutes }} menit</strong>
                    </div>

                    <a href="{{ route('student.exam.start', $exam->id) }}"
                       class="btn-start">
                        Mulai Ujian
                    </a>
                </div>
            @empty
                <div class="text-center text-gray-500 text-sm py-8">
                    Tidak ada ujian tersedia
                </div>
            @endforelse
        </div>

    </div>
</div>

<style>
    :root {
        --theme: {{ session('school_brand.theme') ?? '#2563eb' }};
    }
</style>

@endsection
