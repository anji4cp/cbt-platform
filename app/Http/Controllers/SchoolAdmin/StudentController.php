<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Imports\StudentsImport;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $schoolId = auth()->user()->school_id;

        $classes = Student::where('school_id', $schoolId)
            ->select('class')
            ->distinct()
            ->orderBy('class')
            ->pluck('class');

        $selectedClass = $request->get('class');

        $students = Student::where('school_id', $schoolId)
            ->when($selectedClass, function ($q) use ($selectedClass) {
                $q->where('class', $selectedClass);
            })
            ->orderBy('class')
            ->orderBy('name')
            ->get();

        return view('school_admin.students.index', compact(
            'students',
            'classes',
            'selectedClass'
        ));
    }


    public function create()
    {
        return view('school_admin.students.create');
    }

    public function edit(Student $student)
    {
        // pastikan hanya sekolah sendiri
        abort_if($student->school_id !== auth()->user()->school_id, 403);

        return view('school_admin.students.edit', compact('student'));
    }

    public function update(Request $request, Student $student)
    {
        abort_if($student->school_id !== auth()->user()->school_id, 403);

        $request->validate([
            'name'  => 'required|string',
            'class' => 'required|string',
        ]);

        $student->update([
            'name'  => $request->name,
            'class' => $request->class,
        ]);

        return redirect()
            ->route('students.index')
            ->with('success', 'Data siswa berhasil diperbarui');
    }

    public function store(Request $request)
    {
        $schoolId = auth()->user()->school_id;

        $request->validate([
        'name' => 'required|string|max:100',
        'username' => [
            'required',
            'string',
            'max:50',
            Rule::unique('students')
                ->where(fn ($q) => $q->where('school_id', $schoolId))
        ],
        'class' => 'required|string|max:20',
        'password' => 'required|string|min:4',
    ], [
        'username.unique' => 'Username sudah digunakan di sekolah ini',
    ]);

        Student::create([
            'school_id' => auth()->user()->school_id,
            'name' => $request->name,
            'username' => $request->username,
            'class' => $request->class,
            'password'      => bcrypt($request->password),
            'exam_password' => $request->password,
            'is_active' => true,
        ]);

        return redirect()->route('school.students.index')
                        ->with('success', 'Siswa berhasil ditambahkan');
    }

    public function destroy(Student $student)
    {
        if ($student->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        $student->delete();
        return back();
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx'
        ]);

        $import = new StudentsImport(auth()->user()->school_id);
        Excel::import($import, $request->file('file'));

        return back()->with([
            'import_success' => $import->successRows,
            'import_failed'  => $import->failedRows,
        ]);
    }

    public function toggle($id)
    {
        $student = Student::where('school_id', auth()->user()->school_id)
            ->findOrFail($id);

        $student->update([
            'is_active' => ! $student->is_active
        ]);

        return back();
    }

    public function deleteByClass(Request $request)
    {
        $request->validate([
            'class' => 'required'
        ]);

        $schoolId = auth()->user()->school_id;

        Student::where('school_id', $schoolId)
            ->where('class', $request->class)
            ->delete();

        return redirect()
            ->route('school.students.index')
            ->with('success', 'Semua siswa kelas ' . $request->class . ' berhasil dihapus');
    }

    public function deleteAll()
    {
        $schoolId = auth()->user()->school_id;

        Student::where('school_id', $schoolId)->delete();

        return redirect()
            ->route('school.students.index')
            ->with('success', 'Semua siswa berhasil dihapus');
    }

    public function downloadTemplate()
    {
        $headers = [
            'Content-Type'        => 'xlsx/csv',
            'Content-Disposition' => 'attachment; filename=template_import_siswa.csv',
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');

            // HEADER KOLOM (WAJIB SESUAI IMPORT)
            fputcsv($handle, [
                'name',
                'username',
                'class',
                'password'
            ]);

            // CONTOH DATA
            fputcsv($handle, [
                'Andi Saputra',
                'andi01',
                'X 1',
                '123456'
            ]);

            fputcsv($handle, [
                'Siti Aminah',
                'siti02',
                'X 1',
                '123456'
            ]);

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }



}
