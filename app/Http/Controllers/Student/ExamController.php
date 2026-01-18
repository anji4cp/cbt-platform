<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamSession;
use App\Models\Student;
use App\Models\ExamPackage;
use App\Models\ExamClass;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ExamController extends Controller
{
    /* =========================
       STEP 1: INPUT SERVER ID
    ========================== */
    public function serverForm()
    {
        return view('student.server');
    }

    public function setServer(Request $request)
    {
        $request->validate([
            'server_id' => 'required|string'
        ]);

        $school = School::where('school_id', $request->server_id)->first();

        if (! $school) {
            return back()->withErrors([
                'server_id' => 'Server ID tidak ditemukan'
            ]);
        }

        // 🔒 SIMPAN KE SESSION (INI KUNCI)
        $request->session()->put('student_school_id', $school->id);
        $request->session()->put('school_brand', [
            'name'  => $school->name,
            'logo'  => $school->logo,
            'theme' => $school->theme_color,
        ]);

        // ⛔ JANGAN redirect ke server lagi
        return redirect('/student/login');
    }

    /* =========================
       STEP 2: LOGIN SISWA
    ========================== */
    public function loginForm(Request $request)
    {
        // kalau BELUM pilih server → ke server
        if (! $request->session()->has('student_school_id')) {
            return redirect('/student/server');
        }

        // kalau SUDAH pilih server → tampil login
        return view('student.login');
    }

    public function login(Request $request)
    {
        if (! session()->has('student_school_id')) {
            return redirect()->route('student.server.form');
        }

        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $student = Student::where('school_id', session('student_school_id'))
            ->where('username', $request->username)
            ->first();

        if (! $student) {
            return back()->withErrors([
                'username' => 'Username tidak ditemukan'
            ])->withInput();
        }

        if (! $student->is_active) {
            return back()->withErrors([
                'username' => 'Akun Anda dinonaktifkan. Hubungi pengawas atau admin.'
            ])->withInput();
        }

        if (! Hash::check($request->password, $student->password)) {
            return back()->withErrors([
                'password' => 'Password salah'
            ])->withInput();
        }

        $school = School::find(session('student_school_id'));

        if ($school->status === 'suspend') {
            return back()->withErrors([
                'username' => 'Sekolah sedang disuspend. Hubungi pengawas.'
            ]);
        }

        if ($school->status === 'expired') {
            return back()->withErrors([
                'username' => 'Akses ujian telah berakhir.'
            ]);
        }

        Auth::guard('student')->login($student);

        return redirect()->route('student.exams');
    }


    /* =========================
       STEP 3: LIST UJIAN
    ========================== */
    public function exams()
    {
        $student = Auth::guard('student')->user();

        $exams = Exam::where('school_id', $student->school_id)
            ->where('is_active', true)
            ->whereHas('classes', function ($q) use ($student) {
                $q->where('class_name', $student->class);
            })
            ->get();

        return view('student.exams', compact('exams'));
    }

    public function tokenForm(Exam $exam)
    {
        $student = Auth::guard('student')->user();

        // cek session
        $session = ExamSession::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->first();

        // kalau sudah lolos token → langsung ujian
        if ($session && $session->token_verified) {
            return redirect()->route('student.exam.real', $exam->id);
        }


        // kalau ujian TIDAK pakai token
        if (! $exam->token) {
            return redirect()->route('student.exam.start', $exam->id);
        }

        return view('student.token', compact('exam'));
    }

    public function verifyToken(Request $request, Exam $exam)
    {
        $request->validate(['token' => 'required']);

        if ($exam->token !== $request->token) {
            return back()->withErrors(['token' => 'Token ujian salah']);
        }

        ExamSession::updateOrCreate(
            [
                'exam_id'    => $exam->id,
                'student_id' => auth()->id(),
            ],
            [
                'token_verified' => true
            ]
        );

        return redirect()->route('student.exam.real', $exam->id);
    }



    
    /* =========================
       STEP 4: MULAI UJIAN (FINAL FIX)
    ========================== */
    public function start($id)
    {
        $student = Auth::guard('student')->user();
        abort_if(! $student, 403);

        $exam = Exam::findOrFail($id);

        // pakai token → ke form token
        if ($exam->token) {
            return redirect()->route('student.exam.token.form', $exam->id);
        }

        // tidak pakai token → langsung ujian
        return redirect()->route('student.exam.real', $exam->id);
    }


    public function startReal(Exam $exam)
    {
        $student = Auth::guard('student')->user();
        abort_if(! $student, 403);

        $school = $student->school;

        if (! $school) {
            Auth::guard('student')->logout();

            return redirect('/student/login')
                ->withErrors([
                    'username' => 'Sekolah tidak ditemukan. Hubungi admin.'
                ]);
        }

        if ($school->status === 'suspend') {
            Auth::guard('student')->logout();

            return redirect('/student/login')
                ->withErrors([
                    'username' => 'Sekolah sedang disuspend.'
                ]);
        }

        if (
            $school->status === 'expired' ||
            ($school->expired_at && now()->greaterThan($school->expired_at))
        ) {
            Auth::guard('student')->logout();

            return redirect('/student/login')
                ->withErrors([
                    'username' => 'Akses ujian telah berakhir.'
                ]);
        }


        abort_if($exam->school_id !== $student->school_id, 403);
        abort_if(! $exam->is_active, 403);

        // Cek kelas
        $allowed = ExamClass::where('exam_id', $exam->id)
            ->pluck('class_name')
            ->toArray();

        abort_if(! in_array($student->class, $allowed), 403);

        // Ambil session (PINDAHKAN KE SINI)
        $session = ExamSession::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->first();

        // ❌ JIKA SUDAH SUBMIT → TOLAK MASUK
        if ($session && $session->submitted_at) {
            return redirect()
                ->route('student.exams')
                ->with('error', 'Ujian sudah dikumpulkan dan tidak bisa dibuka kembali.');
        }

        // Paket soal
        $packages = ExamPackage::where('exam_id', $exam->id)->get();
        abort_if($packages->isEmpty(), 404);

        // BUAT SESSION JIKA BELUM ADA
        if (! $session) {
            $now = now();
            $random = $packages->random();

            $session = ExamSession::create([
                'exam_id'         => $exam->id,
                'student_id'      => $student->id,
                'exam_package_id' => $random->id,
                'started_at'      => $now,
                'ends_at'         => $now->copy()->addMinutes($exam->duration_minutes),
            ]);
        }

        // AMANKAN ends_at (DATA LAMA)
        if (is_null($session->ends_at)) {
            $start = $session->started_at ?? $session->created_at ?? now();

            $session->ends_at = Carbon::parse($start)
                ->addMinutes($exam->duration_minutes);

            $session->save();
        }

        // Ambil paket
        $package = ExamPackage::findOrFail($session->exam_package_id);

        // HITUNG SISA WAKTU (ANTI RESET)
        $nowTs = now()->timestamp;
        $endTs = Carbon::parse($session->ends_at)->timestamp;

        if ($nowTs >= $endTs) {
            return redirect()
                ->route('student.exams')
                ->with('error', 'Waktu ujian telah habis');
        }

        $remaining = max(0, $endTs - $nowTs);

        return view('student.exam', [
            'exam'      => $exam,
            'session'   => $session,
            'package'   => $package,
            'remaining' => (int) $remaining,
        ]);
    }



    /* =========================
       AUTOSAVE
    ========================== */
    public function save(Request $request, Exam $exam)
    {
        $student = Auth::guard('student')->user();

        $session = ExamSession::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->firstOrFail();

        // ❌ JIKA SUDAH SUBMIT → KUNCI
        if ($session->submitted_at) {
            return response()->json(['status' => 'locked']);
        }

        if ($exam->token && ! $session->token_verified) {
            return response()->json(['status' => 'token_required'], 403);
        }


        // =============================
        // 🔐 DEVICE LOCK (FIX PALSU)
        // =============================

        // SIMPAN DEVICE PERTAMA KALI
        if (empty($session->device_id)) {
            $session->update([
                'device_id' => $request->device_id
            ]);
        }

        // BARU VALIDASI
        if ($session->device_id !== $request->device_id) {
            return response()->json([
                'status' => 'blocked'
            ], 403);
        }

        // =============================
        // 💾 AUTOSAVE JAWABAN SAJA
        // =============================

        $session->update([
            'answers' => $request->input('answers', [])
        ]);

        return response()->json(['status' => 'saved']);
    }
    /* =========================
       SUBMIT
    ========================== */
    public function submit(Request $request, Exam $exam)
    {
        $student = Auth::guard('student')->user();

        $session = ExamSession::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->firstOrFail();

        // 🔒 JANGAN BOLEH SUBMIT ULANG
        if ($session->submitted_at) {
            return response()->json(['status' => 'locked']);
        }

        if ($exam->token && ! $session->token_verified) {
            return response()->json(['status' => 'token_required'], 403);
        }


        // ===============================
        // ⏱️ VALIDASI MINIMAL WAKTU SUBMIT
        // ===============================
        if ($exam->min_submit_minutes > 0) {

            $startTs = Carbon::parse($session->started_at)->timestamp;
            $nowTs   = now()->timestamp;

            $minAllowedTs = $startTs + ($exam->min_submit_minutes * 60);

            if ($nowTs < $minAllowedTs) {

                $remain = ceil(($minAllowedTs - $nowTs) / 60);

                return response()->json([
                    'status'  => 'too_fast',
                    'message' => "Jawaban baru dapat dikirim setelah {$exam->min_submit_minutes} menit.",
                    'remain'  => $remain
                ], 403);
            }
        }

        // ===== DEVICE CHECK =====
        if ($session->device_id && $session->device_id !== $request->device_id) {
            return response()->json(['status' => 'blocked'], 403);
        }

        if (! $session->device_id) {
            $session->device_id = $request->device_id;
        }

        // ===== HITUNG NILAI =====
        $answers = $request->input('answers', []);

        $keys = json_decode(
            ExamPackage::findOrFail($session->exam_package_id)->answer_key,
            true
        );

        $total = count($keys);
        $correct = 0;

        foreach ($keys as $i => $key) {
            $no = $i + 1;
            if (($answers[$no] ?? null) === $key) {
                $correct++;
            }
        }

        // NILAI MAX 100, TANPA KOMA
        $score = (int) floor(($correct / max(1, $total)) * 100);

        // ✅ WAJIB UPDATE SEMUA
        $session->update([
            'answers'      => $answers,
            'score'        => $score,
            'submitted_at' => now(),
        ]);

        return response()->json([
            'status' => 'submitted',
            'redirect' => route('student.exam.finish', $exam->id)
        ]);

    }

    public function finish(Exam $exam)
    {
        $student = Auth::guard('student')->user();
        abort_if(! $student, 403);

        $session = ExamSession::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->whereNotNull('submitted_at')
            ->firstOrFail();

        $keys = json_decode(
            ExamPackage::findOrFail($session->exam_package_id)->answer_key,
            true
        );

        $answers = $session->answers ?? [];

        $correct = 0;
        foreach ($keys as $i => $key) {
            $no = $i + 1;
            if (($answers[$no] ?? null) === $key) {
                $correct++;
            }
        }

        $wrong = count($keys) - $correct;

        return view('student.exam_finish', [
            'exam'    => $exam,
            'student' => $student,
            'session' => $session,
            'correct' => $correct,
            'wrong'   => $wrong
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('student')->logout();

        // ❌ JANGAN invalidate session
        // ❌ JANGAN hapus student_school_id

        return redirect('/student/login');
    }


}
