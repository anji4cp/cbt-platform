<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\SchoolScopeMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        $middleware->alias([
            'student.auth' => \App\Http\Middleware\StudentAuthMiddleware::class,
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'student.auth' => \App\Http\Middleware\StudentAuthMiddleware::class,
            'student.school' => \App\Http\Middleware\StudentSchoolContext::class,
            'school.scope' => \App\Http\Middleware\SchoolScopeMiddleware::class,
            'student.auth' => \App\Http\Middleware\StudentAuthMiddleware::class,
            'student.school' => \App\Http\Middleware\StudentSchoolContext::class,
            'ensure.school.active' => \App\Http\Middleware\EnsureSchoolActive::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->renderable(function (
            \Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e,
            $request
        ) {

            // ===============================
            // BELUM LOGIN → LOGIN
            // ===============================
            if (
                !auth()->check() &&
                !auth()->guard('student')->check()
            ) {
                return redirect()->route('login');
            }

            // ===============================
            // STUDENT
            // ===============================
            if (auth()->guard('student')->check()) {
                return redirect()->route('student.exams');
            }

            // ===============================
            // ADMIN / SUPER ADMIN
            // ===============================
            $user = auth()->user();

            if ($user) {

                if ($user->role === 'super_admin') {
                    return redirect()->route('superadmin.dashboard');
                }

                if ($user->role === 'admin_school') {
                    return redirect()->route('school.dashboard');
                }
            }

            // ===============================
            // DEFAULT
            // ===============================
            return redirect()->route('login');
        });

    })

    ->create();
