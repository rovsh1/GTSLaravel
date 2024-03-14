<?php

namespace App\Admin\Http\Middleware;

use App\Admin\Models\Administrator\AccessRule;
use App\Admin\Support\Facades\Acl;
use App\Admin\Support\Facades\AppContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AclPermissions
{
    public function __construct()
    {
    }

    public function handle(Request $request, \Closure $next)
    {
        $acl = Acl::getFacadeRoot();

        $this->registerAdministrator($acl);

        $route = $request->route()->getName();
        if ($route && $acl->isRouteAssigned($route) && !$acl->isRouteAllowed($route)) {
            abort(403);
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
//            AppContext::set('superuser', true);
        } else {
//            $context = [];
            $rules = AccessRule::whereAdministrator($administrator->id)->where('flag', true);
            foreach ($rules->cursor() as $r) {
                $permissions->allow($r->resource, $r->permission);
//                $context[$r->resource] = $r->permission;
            }
//            AppContext::set('permissions', $context);
        }
    }
}
