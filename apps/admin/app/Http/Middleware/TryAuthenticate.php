<?php

namespace App\Admin\Http\Middleware;

class TryAuthenticate extends Authenticate
{
    protected function unauthenticated($request, array $guards)
    {
    }
}
