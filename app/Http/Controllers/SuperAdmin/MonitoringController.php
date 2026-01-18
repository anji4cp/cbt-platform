<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\Student;
use App\Models\ExamSession;

class MonitoringController extends Controller
{
    public function index()
    {
        return view('super_admin.monitoring', [
            'totalSchools' => School::count(),
            'totalStudents' => Student::count(),
            'activeSessions' => ExamSession::whereNull('submitted_at')->count(),
        ]);
    }
}
