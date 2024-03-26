<?php

namespace App\Site\Http\Middleware;

use App\Site\Support\Facades\AppContext;
use App\Site\Models\User;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    protected function authenticate($request, array $guards)
    {
        parent::authenticate($request, ['site']);

        /** @var User $user */
        $user = $this->auth->user();
        if (!$user->isActive()) {
            $user->setRememberToken(null);
            $this->unauthenticated($request, ['site']);
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
