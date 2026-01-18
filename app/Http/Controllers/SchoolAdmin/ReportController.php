<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamSession;
use App\Models\Student;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportExport;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $schoolId = auth()->user()->school_id;

        $examId  = $request->exam_id;
        $class   = $request->class;

        $exams = Exam::where('school_id', $schoolId)->get();

        $classes = Student::where('school_id', $schoolId)
            ->select('class')
            ->distinct()
            ->orderBy('class')
            ->pluck('class');

        $query = ExamSession::with(['exam', 'student'])
            ->whereHas('exam', fn ($q) =>
                $q->where('school_id', $schoolId)
            )
            ->whereNotNull('submitted_at');

        if ($examId) {
            $query->where('exam_id', $examId);
        }

        if ($class) {
            $query->whereHas('student', fn ($q) =>
                $q->where('class', $class)
            );
        }

        $sessions = $query->orderBy('created_at')->get();

        return view('school_admin.reports.index', compact(
            'sessions',
            'exams',
            'classes',
            'examId',
            'class'
        ));
    }

    public function export(Request $request)
    {
        return Excel::download(
            new ReportExport(
                auth()->user()->school_id,
                $request->exam_id,
                $request->class
            ),
            'laporan_nilai.xlsx'
        );
    }
}
