<?php

namespace App\Admin\Http\Middleware;

use App\Admin\Models\Administrator\AccessRule;
use App\Admin\Support\Facades\Acl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AclPermissions
{
    public function __construct() {}

    public function handle(Request $request, \Closure $next)
    {
        $acl = Acl::getFacadeRoot();

        $this->registerAdministrator($acl);

        $route = $request->route()->getName();
        if ($route && $acl->isRouteAssigned($route) && !$acl->isRouteAllowed($route)) {
            return abort(403);
        }

        return $next($request);
    }

    private function registerAdministrator($acl): void
    {
        if (!Auth::check()) {
            return;
        }

        $permissions = $acl->permissions();
        $administrator = Auth::user();
        if ($administrator->superuser) {
            $permissions->superuser(true);
        } else {
            //->superuser(true);
            $rules = AccessRule::whereAdministrator($administrator->id)
                ->where('flag', 1);
            foreach ($rules->cursor() as $r) {
                $permissions->allow($r->resource, $r->permission);
            }
        }
    }
}
