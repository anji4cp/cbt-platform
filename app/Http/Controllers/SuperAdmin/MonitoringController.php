<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\Student;
use App\Models\Exam;
use App\Models\ExamSession;

class MonitoringController extends Controller
{
    public function index()
    {
        $schools = School::orderBy('name')
            ->get()
            ->map(function ($school) {

                // Total siswa
                $totalStudents = Student::where('school_id', $school->id)->count();

                // Siswa aktif
                $activeStudents = Student::where('school_id', $school->id)
                    ->where('is_active', true)
                    ->count();

                // Total ujian
                $totalExams = Exam::where('school_id', $school->id)->count();

                // Ujian aktif
                $activeExams = Exam::where('school_id', $school->id)
                    ->where('is_active', true)
                    ->count();

                // Siswa sedang ujian (belum submit)
                $runningSessions = ExamSession::whereHas('exam', function ($q) use ($school) {
                        $q->where('school_id', $school->id);
                    })
                    ->whereNull('submitted_at')
                    ->count();

                return [
                    'name'             => $school->name,
                    'status'           => $school->status,
                    'total_students'   => $totalStudents,
                    'active_students'  => $activeStudents,
                    'total_exams'      => $totalExams,
                    'active_exams'     => $activeExams,
                    'running_sessions' => $runningSessions,
                ];
            });

        return view('super_admin.monitoring', [
            'schools'        => $schools,
            'totalSchools'   => $schools->count(),
            'totalStudents'  => Student::count(),
            'activeSessions' => ExamSession::whereNull('submitted_at')->count(),
        ]);
    }
}
