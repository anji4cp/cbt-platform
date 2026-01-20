<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CbtAppOnly
{
    public function handle($request, Closure $next)
    {
        if ($request->cookie('cbt_app') !== env('CBT_APP_TOKEN')) {
            abort(403, 'Akses hanya melalui aplikasi CBT');
        }

        return $next($request);
    }

}
