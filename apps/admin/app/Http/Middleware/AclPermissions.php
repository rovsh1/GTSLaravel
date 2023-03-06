<?php

namespace App\Admin\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AclPermissions
{
    public function __construct() {}

    public function handle(Request $request, \Closure $next)
    {
        $acl = app('acl');

        $this->registerAdministrator($acl);

        $route = $request->route()->getName();
        if ($acl->isRouteAssigned($route) && !$acl->isRouteAllowed($route)) {
            return abort(403);
        }

        return $next($request);
    }

    private function registerAdministrator($acl): void
    {
        if (!Auth::check()) {
            return;
        }

        $acl->permissions()->superuser(true);
        //TODO load admin rules
        //app('acl')->setUser(Auth::user());
    }
}
