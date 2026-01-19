<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\School;

class EnsureStudentSchoolActive
{
    public function handle(Request $request, Closure $next)
    {
        // WAJIB: siswa harus login
        if (! Auth::guard('student')->check()) {
            return redirect()
                ->route('student.login.form')
                ->withErrors([
                    'username' => 'Silakan login terlebih dahulu.'
                ]);
        }

        $student = Auth::guard('student')->user();

        // WAJIB: ambil school dari SESSION (KUNCI)
        $schoolId = session('student_school_id');

        if (! $schoolId) {
            Auth::guard('student')->logout();

            return redirect()
                ->route('student.server.form')
                ->withErrors([
                    'server_id' => 'Session sekolah hilang. Silakan pilih sekolah lagi.'
                ]);
        }

        $school = School::find($schoolId);

        if (! $school) {
            Auth::guard('student')->logout();

            return redirect()
                ->route('student.server.form')
                ->withErrors([
                    'server_id' => 'Sekolah tidak ditemukan. Hubungi admin.'
                ]);
        }

        // ❌ BLOK STATUS TIDAK AKTIF
        if (in_array($school->status, ['suspend', 'expired'])) {
            Auth::guard('student')->logout();

            return redirect()
                ->route('student.login.form')
                ->withErrors([
                    'username' => 'Sekolah sedang tidak aktif.'
                ]);
        }

        return $next($request);
    }
}
