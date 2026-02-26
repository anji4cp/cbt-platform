<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     * DISABLED: Only Super Admin can create users via SuperAdmin panel
     */
    public function create(): View
    {
        // ⛔ Public registration disabled
        abort(403, 'Registrasi umum tidak diizinkan. Hubungi Super Admin.');
    }

    /**
     * Handle an incoming registration request.
     * DISABLED: Only Super Admin can create users
     */
    public function store(Request $request): RedirectResponse
    {
        // ⛔ Public registration disabled
        abort(403, 'Registrasi umum tidak diizinkan. Hubungi Super Admin.');
    }
}
