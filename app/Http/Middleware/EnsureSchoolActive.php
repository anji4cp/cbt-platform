<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSchoolActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $user = auth()->user();

        if ($user && $user->role === 'admin_school') {
            if ($user->school->status === 'suspend') {
                Auth::logout();
                return redirect('/login')
                    ->withErrors(['email' => 'Sekolah disuspend oleh Super Admin']);
            }
        }

        return $next($request);
    }

}
