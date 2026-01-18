<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SchoolScopeMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Kalau belum login → lanjut saja
        if (! $user) {
            return $next($request);
        }

        // Hanya admin sekolah
        if ($user->role === 'admin_school') {

            // Pastikan admin punya sekolah
            $school = $user->school;

            if (! $school) {
                Auth::logout();
                abort(403, 'Sekolah tidak ditemukan');
            }

            // 🔒 BLOKIR JIKA DISUSPEND
            if ($school->status === 'suspend') {
                Auth::logout();
                abort(403, 'Sekolah sedang disuspend. Hubungi Super Admin.');
            }

            // ⛔ BLOKIR JIKA EXPIRED
            if (
                $school->status === 'expired' ||
                ($school->expired_at && now()->greaterThan($school->expired_at))
            ) {
                Auth::logout();
                abort(403, 'Masa aktif sekolah telah berakhir');
            }

            // (Opsional) share school_id global
            app()->instance('school_id', $school->id);
        }

        return $next($request);
    }
}
