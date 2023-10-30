<?php

namespace App\Shared\Http\Middleware;

use Closure;

class NoCache
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $response
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 0);

        return $response;
    }
}
