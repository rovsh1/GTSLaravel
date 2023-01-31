<?php

namespace GTS\Administrator\UI\Admin\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

use GTS\Administrator\Infrastructure\Facade\Reference\AclFacadeInterface;

class SetAclRules
{
    public function __construct(private readonly AclFacadeInterface $aclFacade) {}

    public function handle(Request $request, \Closure $next)
    {
        if (Auth::check())
            $this->aclFacade->setAdministrator(Auth::id());

        return $next($request);
    }
}
