<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = auth()->user();

        // ===============================
        // VALIDASI ADMIN SEKOLAH
        // ===============================
        if ($user->role === 'admin_school') {
            $school = $user->school;

            if (! $school) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Sekolah tidak ditemukan.'
                ]);
            }

            if ($school->status === 'suspend') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Sekolah sedang disuspend. Hubungi Super Admin.'
                ]);
            }

            if ($school->status === 'expired') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Masa aktif sekolah telah berakhir.'
                ]);
            }
        }

        // ===============================
        // REDIRECT SESUAI ROLE
        // ===============================
        return redirect()->intended($this->redirectByRole($user));
    }

    protected function redirectByRole()
    {
        $user = auth()->user();

        if (! $user) {
            return '/';
        }

        // ===============================
        // ADMIN SEKOLAH → CEK STATUS
        // ===============================
        if ($user->role === 'admin_school') {

            $school = $user->school;

            if (! $school) {
                Auth::logout();
                return redirect()
                    ->route('login')
                    ->withErrors(['email' => 'Sekolah tidak ditemukan']);
            }

            if ($school->status === 'suspend') {
                Auth::logout();
                return redirect()
                    ->route('login')
                    ->withErrors(['email' => 'Sekolah sedang disuspend']);
            }

            if ($school->status === 'expired') {
                Auth::logout();
                return redirect()
                    ->route('login')
                    ->withErrors(['email' => 'Masa aktif sekolah telah berakhir']);
            }

            return route('school.dashboard');
        }

        // ===============================
        // SUPER ADMIN
        // ===============================
        if ($user->role === 'super_admin') {
            return route('superadmin.dashboard');
        }

        return '/';
    }


    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}