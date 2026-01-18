<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect($this->redirectByRole());
    }

    protected function redirectByRole()
    {
        $user = auth()->user();

        $user = User::where('email', $request->email)->first();

        if ($user && $user->role === 'admin_school') {

            $school = $user->school;

            if ($school->status === 'suspend') {
                return back()->withErrors([
                    'email' => 'Sekolah sedang disuspend. Hubungi Super Admin.'
                ]);
            }

            if ($school->status === 'expired') {
                return back()->withErrors([
                    'email' => 'Masa aktif sekolah telah berakhir.'
                ]);
            }
        }

        if ($user->role === 'super_admin') {
            return route('superadmin.dashboard'); // atau superadmin.dashboard
        }

        if ($user->role === 'admin_school') {
            return route('school.dashboard');
        }

        return '/';
    }


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
