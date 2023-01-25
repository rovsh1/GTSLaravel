<?php

namespace GTS\Services\Traveline\UI\Api\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class Authorize
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $username = $request->get('username');
        $password = $request->get('password');
        if ($username === null || $password === null) {
            throw new AccessDeniedHttpException();
        }
        if ($username . $password !== config('services.traveline.username') . config('services.traveline.password')) {
            throw new AccessDeniedHttpException();
        }
        return $next($request);
    }



}
