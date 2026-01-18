<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Exam;
use App\Models\ExamSession;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $schoolId = auth()->user()->school_id;

        // ✅ WAJIB ADA
        $school = \App\Models\School::findOrFail($schoolId);

        /* ===============================
        SERVER ID PUBLIK
        =============================== */
        $serverId = $school->school_id;

        /* ===============================
        STATISTIK ATAS
        =============================== */
        $totalStudents = Student::where('school_id', $schoolId)->count();

        $activeStudents = Student::where('school_id', $schoolId)
            ->where('is_active', true)
            ->count();

        $totalExams = Exam::where('school_id', $schoolId)->count();

        $activeExams = Exam::where('school_id', $schoolId)
            ->where('is_active', true)
            ->count();

        $runningSessions = ExamSession::whereHas('exam', function ($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            })
            ->whereNull('submitted_at')
            ->count();

        $finishedSessions = ExamSession::whereHas('exam', function ($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            })
            ->whereNotNull('submitted_at')
            ->count();

        /* ===============================
        FILTER KELAS
        =============================== */
        $classes = Student::where('school_id', $schoolId)
            ->select('class')
            ->distinct()
            ->orderBy('class')
            ->pluck('class');

        $selectedClass = $request->get('class');

        /* ===============================
        DATA SISWA + STATUS REAL
        =============================== */
        $students = Student::where('school_id', $schoolId)
            ->when($selectedClass, fn ($q) => $q->where('class', $selectedClass))
            ->orderBy('class')
            ->orderBy('name')
            ->get()
            ->map(function ($student) use ($schoolId) {

                $session = ExamSession::where('student_id', $student->id)
                    ->whereHas('exam', fn ($q) => $q->where('school_id', $schoolId))
                    ->latest()
                    ->first();

                if (! $session) {
                    $status = 'Belum Ujian';
                } elseif ($session->submitted_at) {
                    $status = 'Selesai';
                } else {
                    $status = 'Sedang Ujian';
                }

                return [
                    'name'   => $student->name,
                    'class'  => $student->class,
                    'status' => $status,
                ];
            });

        return view('school_admin.dashboard', compact(
            'school',
            'serverId',
            'totalStudents',
            'activeStudents',
            'totalExams',
            'activeExams',
            'runningSessions',
            'finishedSessions',
            'classes',
            'students',
            'selectedClass'
        ));
    }

}
