<?php

namespace App\Shared\Http\Middleware;

use Closure;

class JsonExpected
{
    public function handle($request, Closure $next)
    {
        if (!$request->expectsJson()) {
            return abort(404);
        }

        return $next($request);
    }
}
