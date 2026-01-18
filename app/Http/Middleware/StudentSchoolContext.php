<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StudentSchoolContext
{
    public function handle(Request $request, Closure $next)
    {
        // wajib sudah pilih server / sekolah
        if (! session()->has('school_id')) {
            return redirect()->route('student.server.form');
        }

        return $next($request);
    }
}
