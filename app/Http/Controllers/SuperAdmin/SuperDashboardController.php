<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SuperDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $totalSchools = School::count();

        $activeSchools = School::where('status', 'active')
            ->whereDate('subscription_ends_at', '>=', $today)
            ->count();

        $trialSchools = School::where('status', 'trial')
            ->whereDate('trial_ends_at', '>=', $today)
            ->count();

        $expiredSchools = School::where(function ($q) use ($today) {
                $q->where('status', 'trial')
                ->whereDate('trial_ends_at', '<', $today);
            })
            ->orWhere(function ($q) use ($today) {
                $q->where('status', 'active')
                ->whereDate('subscription_ends_at', '<', $today);
            })
            ->count();

        $recentSchools = School::latest()->take(10)->get();

        return view('super_admin.dashboard', compact(
            'totalSchools',
            'activeSchools',
            'trialSchools',
            'expiredSchools',
            'recentSchools'
        ));
    }

}
