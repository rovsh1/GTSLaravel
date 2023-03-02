<?php

namespace Custom\Illuminate\Http\Middleware;

use Closure;

class Noindex
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        self::addHeader($response);

        return $response;
    }

    public static function addHeader($response)
    {
        $response->header('X-Robots-Tag', 'noindex, follow');
    }
}
