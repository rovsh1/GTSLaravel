<?php

namespace App\Admin\Http\Middleware;

use App\Admin\Services\Acl\Routing\Router;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AclPermission
{
    public function __construct(private readonly Router $aclRouter) {}

    public function handle(Request $request, \Closure $next)
    {
        if (Auth::check()) {
            app('acl')->rules()->superuser(true);
            //TODO load admin rules
            //app('acl')->setUser(Auth::user());
        }

        $route = $request->route()->getName();
        if ($this->aclRouter->isRouteAssigned($route) && !$this->aclRouter->isRouteAllowed($route)) {
            return abort(403);
        }

        return $next($request);
    }
}
