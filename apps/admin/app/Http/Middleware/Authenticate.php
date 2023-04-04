<?php

namespace App\Admin\Http\Middleware;

use App\Admin\Models\Administrator\Administrator;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

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
        }
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param \Illuminate\Http\Request $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return route('auth.login');
        }
    }
}
