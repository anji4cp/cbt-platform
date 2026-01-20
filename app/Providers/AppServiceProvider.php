<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::setLocale('id');

        RateLimiter::for('admin-login', function (Request $request) {
            return Limit::perMinute(5)->by(
                strtolower($request->input('email')).'|'.$request->ip()
            );
        });

        RateLimiter::for('student-login', function (Request $request) {
            return Limit::perMinute(5)->by(
                strtolower($request->input('username')).'|'.$request->ip()
            );
        });
    }

}
