<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Helpers\DriveHelper;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use App\Models\ExamClass;
use App\Models\ExamPackage;
use App\Models\User;

class ExamController extends Controller
{
    public function index()
    {
        $exams = Exam::where('school_id', auth()->user()->school_id)->get();
        return view('school_admin.exams.index', compact('exams'));
    }

    public function create()
    {
        $classes = Student::where('school_id', auth()->user()->school_id)
            ->select('class')
            ->distinct()
            ->orderBy('class')
            ->pluck('class');

        return view('school_admin.exams.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $user = User::find(auth()->id());

        if (! $user || ! $user->school_id) {
            dd('USER TIDAK TERIKAT SEKOLAH', $user);
        }

        $request->validate([
            'subject' => 'required|string',
            'token' => 'required|string',
            'total_questions' => 'required|integer|min:1',
            'duration_minutes' => 'required|integer|min:1',
            'min_submit_minutes' => 'required|integer|min:0',
            'classes' => 'required|array|min:1',
            'packages' => 'required|array|min:1',
            'packages.*.code' => 'required|string',
            'packages.*.pdf_url' => 'required|url',
            'packages.*.answer_key' => 'required|string',
        ]);

        DB::transaction(function () use ($request) {

        $exam = Exam::create([
            'school_id' => auth()->user()->school_id,
            'subject' => $request->subject,
            'token' => $request->token,
            'total_questions' => $request->total_questions,
            'duration_minutes' => $request->duration_minutes,
            'min_submit_minutes' => $request->min_submit_minutes,
            'show_score' => $request->boolean('show_result'),
            'is_active' => false,
        ]);

        // ✅ SIMPAN KELAS
        foreach ($request->classes as $class) {
            ExamClass::create([
                'exam_id' => $exam->id,
                'class_name' => $class,
            ]);
        }

        // 🔥 WAJIB ADA: SIMPAN PAKET SOAL
        foreach ($request->packages as $pkg) {

            ExamPackage::create([
                'exam_id' => $exam->id,
                'package_code' => $pkg['code'], // A / B / C
                'pdf_url' => $pkg['pdf_url'],
                'answer_key' => json_encode(
                    array_map(
                        'trim',
                        explode(',', strtoupper($pkg['answer_key']))
                    )
                ),
            ]);
        }
    });


        return redirect()->route('exams.index')->with('success', 'Ujian berhasil dibuat');
    }

    public function edit(Exam $exam)
    {
        abort_if($exam->school_id !== auth()->user()->school_id, 403);

        $classes = Student::where('school_id', auth()->user()->school_id)
            ->select('class')
            ->distinct()
            ->orderBy('class')
            ->pluck('class');

        $selectedClasses = ExamClass::where('exam_id', $exam->id)
            ->pluck('class_name')
            ->toArray();

        $packages = ExamPackage::where('exam_id', $exam->id)->get();

        return view('school_admin.exams.edit', compact(
            'exam',
            'classes',
            'selectedClasses',
            'packages'
        ));
    }

    public function update(Request $request, Exam $exam)
    {
        abort_if($exam->school_id !== auth()->user()->school_id, 403);

        $request->validate([
            'subject' => 'required|string',
            'token' => 'required|string',
            'total_questions' => 'required|integer|min:1',
            'duration_minutes' => 'required|integer|min:1',
            'min_submit_minutes' => 'required|integer|min:0',
            'classes' => 'required|array|min:1',
            'packages' => 'required|array|min:1',
            'packages.*.code' => 'required|string',
            'packages.*.pdf_url' => 'required|url',
            'packages.*.answer_key' => 'required|string',
        ]);

        DB::transaction(function () use ($request, $exam) {

            // UPDATE EXAM
            $exam->update([
                'subject' => $request->subject,
                'token' => $request->token,
                'total_questions' => $request->total_questions,
                'duration_minutes' => $request->duration_minutes,
                'min_submit_minutes' => $request->min_submit_minutes,
                'show_score' => $request->boolean('show_result'),
            ]);

            // SYNC KELAS (HAPUS → INSERT ULANG)
            ExamClass::where('exam_id', $exam->id)->delete();

            foreach ($request->classes as $class) {
                ExamClass::create([
                    'exam_id' => $exam->id,
                    'class_name' => $class,
                ]);
            }

            // SYNC PAKET (HAPUS → INSERT ULANG)
            ExamPackage::where('exam_id', $exam->id)->delete();

            foreach ($request->packages as $pkg) {
                ExamPackage::create([
                    'exam_id' => $exam->id,
                    'package_code' => $pkg['code'],
                    'pdf_url' => $pkg['pdf_url'],
                    'answer_key' => json_encode(
                        array_map('trim', explode(',', strtoupper($pkg['answer_key'])))
                    ),
                ]);
            }
        });

        return redirect()
            ->route('exams.index')
            ->with('success', 'Ujian berhasil diperbarui');
    }

    public function toggle(Exam $exam)
    {
        abort_if($exam->school_id !== auth()->user()->school_id, 403);

        $exam->update([
            'is_active' => ! $exam->is_active
        ]);

        return back();
    }

    public function destroy(Exam $exam)
    {
        // Pastikan hanya sekolah sendiri
        abort_if($exam->school_id !== auth()->user()->school_id, 403);

        // Optional: cegah hapus jika sedang aktif
        if ($exam->is_active) {
            return back()->with('error', 'Ujian masih aktif, nonaktifkan dulu.');
        }

        $exam->delete();

        return back()->with('success', 'Ujian berhasil dihapus.');
    }

}
