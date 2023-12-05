<?php

namespace App\Hotel\Http\Middleware;

use App\Admin\Models\Administrator\Administrator;
use App\Admin\Support\Facades\AppContext;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    protected function authenticate($request, array $guards)
    {
        parent::authenticate($request, ['admin']);

        /** @var Administrator $administrator */
        $administrator = $this->auth->user();
        if (!$administrator->isActive() && !$administrator->isSuperuser()) {
            $administrator->setRememberToken(null);
            $this->unauthenticated($request, ['admin']);
        } else {
            AppContext::setAdministrator($administrator->id, $administrator->presentation);
        }
    }

    /**
     * @param Request $request
     * @return string|null
     */
    protected function redirectTo(Request $request)
    {
        if (!$request->expectsJson()) {
            $query = ($q = $request->query())
                ? '?' . http_build_query($q) : '';

            return route('auth.login', [
                'url' => "/{$request->path()}$query"
            ]);
        }

        return null;
    }
}
