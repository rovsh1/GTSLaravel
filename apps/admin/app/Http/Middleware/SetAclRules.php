<?php

namespace App\Admin\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SetAclRules
{
    public function __construct() {}

    public function handle(Request $request, \Closure $next)
    {
//        if (Auth::check())
//            $this->aclFacade->setAdministrator(Auth::id());

        return $next($request);
    }
}
