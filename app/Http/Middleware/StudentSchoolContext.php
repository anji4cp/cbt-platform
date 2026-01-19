<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StudentSchoolContext
{
    public function handle(Request $request, Closure $next)
    {
        
        // ✅ WAJIB pakai key yang sama dengan controller
        if (! session()->has('student_school_id')) {
            return redirect()->route('student.server.form');
        }

        return $next($request);
    }
}
