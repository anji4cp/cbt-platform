<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use App\Models\ExamClass;
use App\Models\ExamPackage;
use App\Models\User;
use Illuminate\Validation\Rule; // ✅ DITAMBAH
use Illuminate\Database\QueryException; // ✅ DITAMBAH

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
        $request->validate([
            'subject' => 'required|string',
            'token' => [
                'required',
                'string',
                Rule::unique('exams', 'token'), // ✅ TOKEN UNIK
            ],
            'total_questions' => 'required|integer|min:1',
            'duration_minutes' => 'required|integer|min:1',
            'min_submit_minutes' => 'required|integer|min:0',
            'classes' => 'required|array|min:1',
            'packages' => 'required|array|min:1',
            'packages.*.code' => 'required|string',
            'packages.*.pdf_url' => 'required|url',
            'packages.*.answer_key' => 'required|string',
        ]);

        try {
            DB::transaction(function () use ($request) {

                $exam = Exam::create([
                    'school_id' => auth()->user()->school_id,
                    'subject' => $request->subject,
                    'token' => strtoupper($request->token), // ✅ NORMALISASI
                    'total_questions' => $request->total_questions,
                    'duration_minutes' => $request->duration_minutes,
                    'min_submit_minutes' => $request->min_submit_minutes,
                    'show_score' => $request->boolean('show_result'),
                    'is_active' => false,
                ]);

                foreach ($request->classes as $class) {
                    ExamClass::create([
                        'exam_id' => $exam->id,
                        'class_name' => $class,
                    ]);
                }

                foreach ($request->packages as $pkg) {
                    ExamPackage::create([
                        'exam_id' => $exam->id,
                        'package_code' => strtoupper($pkg['code']),
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
        } catch (QueryException $e) {
            if ($e->getCode() == 23505) { // ✅ DOUBLE SAFETY
                return back()->withErrors([
                    'token' => 'Token ujian sudah digunakan.',
                ]);
            }
            throw $e;
        }

        return redirect()->route('school.exams.index')->with('success', 'Ujian berhasil dibuat');
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
            'token' => [
                'required',
                'string',
                Rule::unique('exams', 'token')->ignore($exam->id), // ✅ IGNORE ID SENDIRI
            ],
            'total_questions' => 'required|integer|min:1',
            'duration_minutes' => 'required|integer|min:1',
            'min_submit_minutes' => 'required|integer|min:0',
            'classes' => 'required|array|min:1',
            'packages' => 'required|array|min:1',
            'packages.*.code' => 'required|string',
            'packages.*.pdf_url' => 'required|url',
            'packages.*.answer_key' => 'required|string',
        ]);

        try {
            DB::transaction(function () use ($request, $exam) {

                $exam->update([
                    'subject' => $request->subject,
                    'token' => strtoupper($request->token), // ✅ NORMALISASI
                    'total_questions' => $request->total_questions,
                    'duration_minutes' => $request->duration_minutes,
                    'min_submit_minutes' => $request->min_submit_minutes,
                    'show_score' => $request->boolean('show_result'),
                ]);

                ExamClass::where('exam_id', $exam->id)->delete();
                foreach ($request->classes as $class) {
                    ExamClass::create([
                        'exam_id' => $exam->id,
                        'class_name' => $class,
                    ]);
                }

                ExamPackage::where('exam_id', $exam->id)->delete();
                foreach ($request->packages as $pkg) {
                    ExamPackage::create([
                        'exam_id' => $exam->id,
                        'package_code' => strtoupper($pkg['code']),
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
        } catch (QueryException $e) {
            if ($e->getCode() == 23505) {
                return back()->withErrors([
                    'token' => 'Token ujian sudah digunakan.',
                ]);
            }
            throw $e;
        }

        return redirect()->route('school.exams.index')->with('success', 'Ujian berhasil diperbarui');
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
        abort_if($exam->school_id !== auth()->user()->school_id, 403);

        if ($exam->is_active) {
            return back()->with('error', 'Ujian masih aktif, nonaktifkan dulu.');
        }

        $exam->delete();

        return back()->with('success', 'Ujian berhasil dihapus.');
    }
}
