<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SuperAdmin\SchoolController;
use App\Http\Controllers\SuperAdmin\SchoolAdminController;
use App\Http\Controllers\SuperAdmin\MonitoringController;
use App\Http\Controllers\SuperAdmin\SuperDashboardController;

use App\Http\Controllers\SchoolAdmin\DashboardController;
use App\Http\Controllers\SchoolAdmin\ProfileController as SchoolProfileController;
use App\Http\Controllers\SchoolAdmin\StudentController;
use App\Http\Controllers\SchoolAdmin\ExamController as SchoolExamController;
use App\Http\Controllers\SchoolAdmin\ReportController;
use App\Http\Controllers\SchoolAdmin\ExamCardController;

use App\Http\Controllers\Student\ExamController as StudentExamController;

/*
|--------------------------------------------------------------------------
| ROOT & FALLBACK
|--------------------------------------------------------------------------
*/
Route::get('/', function () {

    if (!auth()->check() && !auth()->guard('student')->check()) {
        return redirect()->route('login');
    }

    if (auth()->guard('student')->check()) {
        return redirect()->route('student.exams');
    }

    if (auth()->check()) {
        if (auth()->user()->role === 'super_admin') {
            return redirect()->route('superadmin.dashboard');
        }

        if (auth()->user()->role === 'admin_school') {
            return redirect()->route('school.dashboard');
        }
    }

    return redirect()->route('login');
});

Route::fallback(function () {
    return redirect('/');
});


/*
|--------------------------------------------------------------------------
| AUTH DEFAULT (LARAVEL)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';


/*
|--------------------------------------------------------------------------
| SUPER ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:super_admin'])
    ->prefix('super-admin')
    ->name('superadmin.')
    ->group(function () {

        Route::get('/dashboard', [SuperDashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('schools', SchoolController::class)->except(['show']);

        Route::get('monitoring', [MonitoringController::class, 'index'])
            ->name('monitoring');

        Route::get('/schools/{school}/admin/create',
            [SchoolAdminController::class, 'create']
        )->name('schools.admin.create');

        Route::post('/schools/{school}/admin',
            [SchoolAdminController::class, 'store']
        )->name('schools.admin.store');
    });


/*
|--------------------------------------------------------------------------
| ADMIN SEKOLAH
|--------------------------------------------------------------------------
*/
Route::middleware([
        'auth',
        'role:admin_school',
        'school.scope',
        'ensure.school.active', // ADMIN ONLY
    ])
    ->prefix('school-admin')
    ->name('school.')
    ->group(function () {

        // DASHBOARD
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // PROFILE
        Route::get('/profile', [SchoolProfileController::class, 'index'])->name('profile');
        Route::get('/profile/edit', [SchoolProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [SchoolProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [SchoolProfileController::class, 'updatePassword'])->name('profile.password');

        // SISWA
        Route::post('students/delete-by-class', [StudentController::class, 'deleteByClass'])
            ->name('students.deleteByClass');

        Route::delete('students/delete-all', [StudentController::class, 'deleteAll'])
            ->name('students.deleteAll');

        Route::resource('students', StudentController::class)->except(['show']);

        Route::post('students/import', [StudentController::class, 'import'])
            ->name('students.import');

        Route::patch('students/{student}/toggle', [StudentController::class, 'toggle'])
            ->name('students.toggle');

        Route::get('students/template', [StudentController::class, 'downloadTemplate'])
            ->name('students.template');

        // UJIAN
        Route::resource('exams', SchoolExamController::class);
        Route::patch('exams/{exam}/toggle', [SchoolExamController::class, 'toggle'])
            ->name('exams.toggle');

        // AJAX CEK TOKEN (ADMIN)
        Route::get('exams/check-token', function () {
            return response()->json([
                'exists' => \App\Models\Exam::where('token', request('token'))->exists()
            ]);
        });

        // LAPORAN
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/export', [ReportController::class, 'export'])->name('reports.export');

        // KARTU UJIAN
        Route::get('exam-cards', [ExamCardController::class, 'index'])->name('exam-cards.index');
        Route::post('exam-cards/print', [ExamCardController::class, 'print'])->name('exam-cards.print');
    });


/*
|--------------------------------------------------------------------------
| SISWA – STEP 1: PILIH SERVER (TANPA AUTH)
|--------------------------------------------------------------------------
*/
Route::prefix('student')
    ->name('student.')
    ->middleware(['web'])
    ->group(function () {

        Route::get('/server', [StudentExamController::class, 'serverForm'])
            ->name('server.form');

        Route::post('/server', [StudentExamController::class, 'setServer'])
            ->name('server.set');
    });


/*
|--------------------------------------------------------------------------
| SISWA – STEP 2: LOGIN
|--------------------------------------------------------------------------
*/
Route::prefix('student')
    ->name('student.')
    ->middleware([
        'web',
        'student.school.context',
    ])
    ->group(function () {

        Route::get('/login', [StudentExamController::class, 'loginForm'])
            ->name('login.form');

        Route::post('/login', [StudentExamController::class, 'login'])
            ->name('login');

        Route::post('/logout', [StudentExamController::class, 'logout'])
            ->name('logout');
    });


/*
|--------------------------------------------------------------------------
| SISWA – STEP 3: AREA UJIAN (SUDAH LOGIN)
|--------------------------------------------------------------------------
*/
Route::prefix('student')
    ->name('student.')
    ->middleware([
        'web',
        'student.school.context',
        'student.school.active',
        'auth:student',
    ])
    ->group(function () {

        Route::get('/exams', [StudentExamController::class, 'exams'])
            ->name('exams');

        Route::get('/exam/{exam}', [StudentExamController::class, 'start'])
            ->name('exam.start');

        Route::post('/exam/{exam}/save', [StudentExamController::class, 'save'])
            ->name('exam.save');

        Route::post('/exam/{exam}/submit', [StudentExamController::class, 'submit'])
            ->name('exam.submit');

        Route::get('/exam/{exam}/finish', [StudentExamController::class, 'finish'])
            ->name('exam.finish');

        // TOKEN
        Route::get('/exam/{exam}/token', [StudentExamController::class, 'tokenForm'])
            ->name('exam.token.form');

        Route::post('/exam/{exam}/token', [StudentExamController::class, 'verifyToken'])
            ->name('exam.token');

        Route::get('/exam/{exam}/start-real', [StudentExamController::class, 'startReal'])
            ->name('exam.real');
    });
