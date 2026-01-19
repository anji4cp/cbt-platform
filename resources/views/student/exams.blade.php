@extends('layouts.student')

@section('content')

<style>
    .exam-container {
        max-width: 1200px;
        margin: 0 auto;
        width: 100%;
    }

    .exam-card {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(0,0,0,.08);
        overflow: hidden;
    }

    .exam-header {
        padding: 18px 24px;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #f8fafc;
    }

    .exam-header h2 {
        font-size: 18px;
        font-weight: 600;
        margin: 0;
        color: #111827;
    }

    /* ================= DESKTOP TABLE ================= */
    .exam-table-wrapper {
        overflow-x: auto;
    }

    .exam-table {
        width: 100%;
        min-width: 700px;
        border-collapse: collapse;
    }

    .exam-table th {
        background: #f9fafb;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: #6b7280;
        padding: 14px 18px;
        text-align: left;
        white-space: nowrap;
    }

    .exam-table td {
        padding: 16px 18px;
        border-top: 1px solid #e5e7eb;
        font-size: 14px;
        color: #374151;
    }

    .exam-table tr:hover {
        background: #f8fafc;
    }

    /* ================= MOBILE CARD ================= */
    .exam-mobile {
        display: none;
        padding: 16px;
        gap: 14px;
    }

    .exam-item {
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 16px;
        background: #ffffff;
    }

    .exam-item h3 {
        font-size: 15px;
        font-weight: 600;
        margin: 0 0 6px;
        color: #111827;
    }

    .exam-item .meta {
        font-size: 12px;
        color: #6b7280;
        margin-bottom: 12px;
    }

    .badge {
        background: #e0e7ff;
        color: #3730a3;
        font-size: 12px;
        padding: 4px 12px;
        border-radius: 999px;
        font-weight: 600;
        display: inline-block;
    }

    .btn-start {
        display: inline-block;
        padding: 8px 18px;
        background: var(--theme, #2563eb);
        color: #fff;
        font-size: 13px;
        font-weight: 600;
        border-radius: 999px;
        text-decoration: none;
        text-align: center;
        transition: .2s;
    }

    .btn-start:hover {
        opacity: .9;
        transform: translateY(-1px);
    }

    /* ================= RESPONSIVE ================= */
    @media (max-width: 640px) {
        .exam-table-wrapper {
            display: none;
        }

        .exam-mobile {
            display: grid;
        }

        .exam-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 6px;
        }
    }
</style>

<div class="exam-container">

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
@endsection
