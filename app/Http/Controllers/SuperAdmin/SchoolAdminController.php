<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SchoolAdminController extends Controller
{
    public function create(School $school)
    {
        return view('super_admin.school_admins.create', compact('school'));
    }

    public function store(Request $request, School $school)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin_school',   // ← WAJIB
            'school_id' => $school->id, // ← WAJIB
        ]);

        return redirect()->route('schools.index')
        ->with('success', 'Admin Sekolah berhasil ditambahkan');
    }
}
