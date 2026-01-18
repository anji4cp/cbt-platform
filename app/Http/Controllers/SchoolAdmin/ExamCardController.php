<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\School;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExamCardController extends Controller
{
    public function index()
    {
        $schoolId = auth()->user()->school_id;

        $classes = Student::where('school_id', $schoolId)
            ->select('class')
            ->distinct()
            ->orderBy('class')
            ->pluck('class');

        return view('school_admin.exam_cards.index', compact('classes'));
    }

    public function print(Request $request)
    {
        $schoolId = auth()->user()->school_id;
        $school   = School::findOrFail($schoolId);

        $request->validate([
            'class' => 'required',
            'card_date' => 'nullable|date'
        ]);

        $students = Student::where('school_id', $schoolId)
            ->where('class', $request->class)
            ->orderBy('name')
            ->get();

        // ✅ TANGGAL CUSTOM (DEFAULT = HARI INI)
        $date = $request->filled('card_date')
            ? Carbon::parse($request->card_date)
            : Carbon::now();

        $config = [
            'kop'        => $request->kop
                ?? 'DINAS PENDIDIKAN DAN KEBUDAYAAN PROVINSI LAMPUNG',

            'schoolName' => $school->name,

            'title'      => $request->title
                ?? 'KARTU PESERTA UJIAN',

            'showPass'   => $request->boolean('show_password'),

            'position'   => $request->position
                ?? 'Kepala Sekolah',

            'signName'   => $request->sign_name
                ?? '',

            'nip'        => $request->nip
                ?? '',

            // 🔥 TANGGAL SUDAH FIX & INDONESIA
            'date'       => $date->translatedFormat('l, d - m - Y'),

            'logo'       => $school->logo,
        ];

        return view('school_admin.exam_cards.print', compact(
            'students',
            'config'
        ));
    }

}
