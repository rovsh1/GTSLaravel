<?php

namespace Pkg\Supplier\Traveline\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class Authorize
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response|RedirectResponse) $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        //@todo refactor
        module('Traveline')->boot();
//        $username = $request->get('username');
//        $password = $request->get('password');
//        if ($username === null || $password === null) {
//            throw new AccessDeniedHttpException();
//        }
//        $exceptedAuthHash = config('suppliers.traveline.auth.username') . config('suppliers.traveline.auth.password');
//        if ($username . $password !== $exceptedAuthHash) {
//            throw new AccessDeniedHttpException();
//        }
        return $next($request);
    }


}
