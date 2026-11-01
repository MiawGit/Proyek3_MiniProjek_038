<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! $request->user() || ! in_array($request->user()->role->value, $roles, true)) {
            abort(403, 'Anda tidak memiliki kewenangan untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}
