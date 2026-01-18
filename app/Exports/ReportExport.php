<?php

namespace App\Exports;

use App\Models\ExamSession;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReportExport implements FromCollection, WithHeadings
{
    protected $schoolId;
    protected $examId;
    protected $class;

    public function __construct($schoolId, $examId = null, $class = null)
    {
        $this->schoolId = $schoolId;
        $this->examId   = $examId;
        $this->class    = $class;
    }

    public function collection(): Collection
    {
        $query = ExamSession::with(['exam', 'student'])
            ->whereHas('exam', fn ($q) =>
                $q->where('school_id', $this->schoolId)
            )
            ->whereNotNull('submitted_at');

        if ($this->examId) {
            $query->where('exam_id', $this->examId);
        }

        if ($this->class) {
            $query->whereHas('student', fn ($q) =>
                $q->where('class', $this->class)
            );
        }

        return $query->get()->map(function ($s) {
            return [
                'Nama Siswa' => $s->student->name,
                'Username'  => $s->student->username,
                'Kelas'     => $s->student->class,
                'Ujian'     => $s->exam->subject,
                'Nilai'     => $s->score,
                'Waktu'     => optional($s->submitted_at)->format('d-m-Y H:i'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama Siswa',
            'Username',
            'Kelas',
            'Ujian',
            'Nilai',
            'Waktu Submit',
        ];
    }
}
