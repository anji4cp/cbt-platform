<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SchoolController extends Controller
{
    /* =========================
       INDEX
    ========================== */
    public function index()
    {
        $schools = School::orderBy('created_at', 'desc')->get();
        $schools = School::with('admins')->orderBy('created_at', 'desc')->get();

        return view('super_admin.schools.index', compact('schools'));
    }

    /* =========================
       CREATE
    ========================== */
    public function create()
    {
        return view('super_admin.schools.create');
    }

    /* =========================
       STORE (TAMBAH SEKOLAH)
    ========================== */
    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'plan_type'      => 'required|in:trial,active',
            'duration_unit'  => 'nullable|in:days,months,years',
            'duration_value' => 'nullable|integer|min:1',
        ]);

        $now = now();

        // GENERATE SCHOOL ID (SERVER ID)
        $schoolId = strtoupper(Str::random(10));

        $data = [
            'name'      => $request->name,
            'school_id' => $schoolId,
            'status'    => $request->plan_type,
        ];

        // ===== TRIAL =====
        if ($request->plan_type === 'trial') {
            $data['trial_ends_at'] = $now->copy()->addDays(7);
            $data['subscription_ends_at'] = null;
        }

        // ===== ACTIVE =====
        if ($request->plan_type === 'active') {

            $value = (int) ($request->duration_value ?? 1);
            $unit  = $request->duration_unit ?? 'months';

            $endsAt = match ($unit) {
                'days'   => now()->copy()->addDays($value),
                'months' => now()->copy()->addMonths($value),
                'years'  => now()->copy()->addYears($value),
            };

            $data['subscription_ends_at'] = $endsAt;
            $data['trial_ends_at'] = null;
        }


        School::create($data);

        return redirect()
            ->route('superadmin.schools.index')
            ->with('success', 'Sekolah berhasil ditambahkan');
    }

    /* =========================
       EDIT
    ========================== */
    public function edit(School $school)
    {
        return view('super_admin.schools.edit', compact('school'));
    }

    /* =========================
       UPDATE (EDIT SEKOLAH)
    ========================== */
    public function update(Request $request, School $school)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'plan_type'      => 'required|in:trial,active,expired,suspend',
            'duration_unit'  => 'nullable|in:days,months,years',
            'duration_value' => 'nullable|integer|min:1',
        ]);

        $now = now();

        $school->name   = $request->name;
        $school->status = $request->plan_type;

        // ===== TRIAL =====
        if ($request->plan_type === 'trial') {
            $school->trial_ends_at = $now->copy()->addDays(7);
            $school->subscription_ends_at = null;
        }

        // ===== ACTIVE =====
        if ($request->plan_type === 'active') {

            if ($request->filled('duration_value')) {

                $value = (int) $request->duration_value;
                $unit  = $request->duration_unit ?? 'months';

                $school->subscription_ends_at = match ($unit) {
                    'days'   => now()->addDays($value),
                    'months' => now()->addMonths($value),
                    'years'  => now()->addYears($value),
                };
            }

            $school->trial_ends_at = null;
        }

        // ===== EXPIRED / SUSPEND =====
        if (in_array($request->plan_type, ['expired', 'suspend'])) {
            // tanggal tetap, hanya status
            // (tidak dihapus supaya histori aman)
        }

        $school->save();

        return redirect()
            ->route('superadmin.schools.index')
            ->with('success', 'Sekolah berhasil diperbarui');
    }

    /* =========================
       DESTROY (OPSIONAL)
    ========================== */
    public function destroy(School $school)
    {
        $school->delete();

        return redirect()
            ->route('superadmin.schools.index')
            ->with('success', 'Sekolah dihapus');
    }
}
