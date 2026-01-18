<?php

namespace App\Http\Controllers\SchoolAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $school = auth()->user()->school;

        return view('school_admin.profile.index', compact('school'));
    }

    public function edit()
    {
        $school = auth()->user()->school;

        return view('school_admin.profile.edit', compact('school'));
    }

    public function update(Request $request)
    {
        $school = auth()->user()->school;

        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'theme_color' => 'required|string',
            'contact'     => 'nullable|string',
            'logo'        => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            if ($school->logo) {
                Storage::disk('public')->delete($school->logo);
            }

            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $school->update($data);

        return redirect()
            ->route('school.profile')
            ->with('success', 'Profil sekolah berhasil diperbarui');
    }
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = auth()->user();

        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Password lama salah'
            ]);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // setelah update password
        auth()->logout();
        return redirect()->route('login')
            ->with('success','Password diubah, silakan login ulang');

    }
}
