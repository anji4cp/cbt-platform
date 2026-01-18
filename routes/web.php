<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\SchoolController;
use App\Http\Controllers\SuperAdmin\SchoolAdminController;
use App\Http\Controllers\SchoolAdmin\ProfileController as SchoolProfileController;
use App\Http\Controllers\Student\ExamController as StudentExamController;
use App\Http\Controllers\SchoolAdmin\StudentController;
use App\Http\Controllers\SchoolAdmin\ExamController;
use App\Http\Controllers\SchoolAdmin\ReportController;
use App\Http\Controllers\SuperAdmin\MonitoringController;
use App\Http\Controllers\SchoolAdmin\DashboardController;
use App\Http\Controllers\SchoolAdmin\ExamController as SchoolExamController;
use App\Http\Controllers\SchoolAdmin\ExamCardController;
use App\Http\Controllers\SuperAdmin\SuperDashboardController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth','role:super_admin'])
    ->prefix('super-admin')
    ->group(function () {
        Route::get('/dashboard', 
            [SuperDashboardController::class, 'index']
        )->name('superadmin.dashboard');
        Route::resource('schools', SchoolController::class);
        Route::get('monitoring', [MonitoringController::class, 'index'])->name('super.monitoring');
        Route::get('/schools/{school}/admin/create', [SchoolAdminController::class, 'create'])->name('schools.admin.create');
        Route::post('/schools/{school}/admin', [SchoolAdminController::class, 'store'])->name('schools.admin.store');
    });

Route::middleware(['auth', 'role:admin_school', 'school.scope','ensure.school.active'])
    ->prefix('school-admin')
    ->name('school.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/profile', [SchoolProfileController::class, 'index'])->name('profile');
        Route::get('/profile/edit', [SchoolProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [SchoolProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [SchoolProfileController::class, 'updatePassword'])->name('profile.password');

        // SISWA
        Route::post('students/delete-by-class', [StudentController::class, 'deleteByClass'])->name('students.deleteByClass');
        Route::delete('students/delete-all', [StudentController::class, 'deleteAll'])->name('students.deleteAll');
        Route::resource('students', StudentController::class)->except(['show']);
        Route::post('students/import', [StudentController::class, 'import'])->name('students.import');
        Route::patch('students/{student}/toggle', [StudentController::class, 'toggle'])->name('students.toggle');
        Route::get('students/template', [StudentController::class, 'downloadTemplate'])->name('students.template');

        // UJIAN
        Route::resource('exams', ExamController::class);
        Route::patch('exams/{exam}/toggle', [ExamController::class, 'toggle'])->name('exams.toggle');

        // LAPORAN
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/export', [ReportController::class, 'export'])->name('reports.export');

        // KARTU UJIAN
        Route::get('exam-cards', [ExamCardController::class, 'index'])->name('exam-cards.index');
        Route::post('exam-cards/print', [ExamCardController::class, 'print'])->name('exam-cards.print');
    });
        /*
        |--------------------------------------------------------------------------
        | STEP 3: AREA SISWA (SUDAH LOGIN)
        |--------------------------------------------------------------------------
        */
 Route::prefix('student')->name('student.')->group(function () {

    // STEP 1: SERVER
    Route::get('/server', [StudentExamController::class, 'serverForm'])->name('server.form');
    Route::post('/server', [StudentExamController::class, 'setServer'])->name('server.set');

    // STEP 2: LOGIN
    Route::get('/login', [StudentExamController::class, 'loginForm'])->name('login.form');
    Route::post('/login', [StudentExamController::class, 'login'])->name('login');
    Route::post('/logout', [StudentExamController::class, 'logout'])->name('logout');

    // STEP 3: AREA SISWA
Route::middleware('auth:student')->group(function () {

    Route::get('/exams', [StudentExamController::class, 'exams'])->name('exams');

    Route::get('/exam/{exam}', [StudentExamController::class, 'start'])->name('exam.start');
    Route::post('/exam/{exam}/save', [StudentExamController::class, 'save'])->name('exam.save');
    Route::post('/exam/{exam}/submit', [StudentExamController::class, 'submit'])->name('exam.submit');
    Route::get('/exam/{exam}/finish', [StudentExamController::class, 'finish'])->name('exam.finish');
    });
  });
Route::middleware('auth:student')->prefix('student')->group(function () {

    // FORM TOKEN
    Route::get('/exam/{exam}/token',
        [StudentExamController::class, 'tokenForm']
    )->name('student.exam.token.form');

    // SUBMIT TOKEN
    Route::post('/exam/{exam}/token',
        [StudentExamController::class, 'verifyToken']
    )->name('student.exam.token');

    // MASUK UJIAN SETELAH TOKEN
    Route::get('/exam/{exam}/start-real',
        [StudentExamController::class, 'startReal']
    )->name('student.exam.real');

});

require __DIR__.'/auth.php';
