<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SchoolScopeMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        // hanya untuk admin sekolah
        if ($user && $user->role === 'admin_school') {
            // share school_id global (opsional)
            app()->instance('school_id', $user->school_id);
        }

        $school = auth()->user()->school;

        if ($school->current_status === 'expired') {
            Auth::logout();
            abort(403, 'Masa langganan sekolah telah berakhir');
        }


        return $next($request);
    }
}
