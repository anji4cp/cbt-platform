@extends('layouts.student')

@section('content')
<style>
    .exam-container {
        max-width: 900px;
        margin: 0 auto;
    }

    .exam-card {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(0,0,0,.08);
        overflow: hidden;
    }

    .exam-header {
        padding: 18px 22px;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .exam-header h2 {
        font-size: 18px;
        font-weight: 600;
        margin: 0;
        color: #111827;
    }

    .exam-table {
        width: 100%;
        border-collapse: collapse;
    }

    .exam-table th {
        background: #f9fafb;
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: #6b7280;
        padding: 14px 16px;
        text-align: left;
    }

    .exam-table td {
        padding: 14px 16px;
        border-top: 1px solid #e5e7eb;
        font-size: 14px;
        color: #374151;
    }

    .exam-table tr:hover {
        background: #f8fafc;
    }

    .badge {
        background: #e0e7ff;
        color: #3730a3;
        font-size: 12px;
        padding: 4px 10px;
        border-radius: 999px;
        font-weight: 600;
    }

    .btn-start {
        display: inline-block;
        padding: 8px 16px;
        background: #2563eb;
        color: #fff;
        font-size: 13px;
        font-weight: 600;
        border-radius: 999px;
        text-decoration: none;
        transition: all .2s ease;
    }

    .btn-start:hover {
        background: #1d4ed8;
        transform: translateY(-1px);
    }

    .alert-error {
        background: #fee2e2;
        color: #991b1b;
        padding: 12px 16px;
        border-radius: 12px;
        margin-bottom: 16px;
        font-size: 14px;
    }

    /* MOBILE */
    @media (max-width: 640px) {
        .exam-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 6px;
        }

        .exam-table th,
        .exam-table td {
            padding: 12px;
            font-size: 13px;
        }
    }
</style>

<div class="exam-container">

    @if(session('error'))
        <div class="alert-error">
            {{ session('error') }}
        </div>
    @endif

    <div class="exam-card">
        <div class="exam-header">
            <h2>📘 Daftar Ujian Aktif</h2>
            <div style="font-size:12px;color:#6b7280">
                {{ count($exams) }} ujian tersedia
            </div>
        </div>

        <table class="exam-table">
            <thead>
                <tr>
                    <th>Mata Pelajaran</th>
                    <th>Durasi</th>
                    <th style="width:140px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($exams as $exam)
                    <tr>
                        <td>
                            <strong>{{ $exam->subject }}</strong>
                        </td>
                        <td>
                            <span class="badge">
                                {{ $exam->duration_minutes }} menit
                            </span>
                        </td>
                        <td>
                            <a
                                class="btn-start"
                                href="{{ route('student.exam.start', $exam->id) }}">
                                Mulai Ujian
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align:center;color:#6b7280;padding:24px">
                            Tidak ada ujian tersedia
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
