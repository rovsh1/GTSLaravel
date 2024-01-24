<?php

namespace App\Hotel\Http\Middleware;

use App\Hotel\Models\Administrator;
use App\Hotel\Support\Facades\AppContext;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    protected function authenticate($request, array $guards)
    {
        parent::authenticate($request, ['hotel']);

        /** @var Administrator $user */
        $user = $this->auth->user();
        if (!$user->isActive()) {
            $user->setRememberToken(null);
            $this->unauthenticated($request, ['hotel']);
        } else {
            AppContext::setUserId($user->id);
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
                ? '?' . http_build_query($q)
                : '';

            return route('auth.login', [
                'url' => "{$request->path()}$query"
            ]);
        }

        return null;
    }
}
