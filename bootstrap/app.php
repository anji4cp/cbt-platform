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
    ->withExceptions(function ($exceptions) {
        //
    })
    ->create();
