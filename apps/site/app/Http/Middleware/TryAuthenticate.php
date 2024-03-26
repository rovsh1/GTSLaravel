<?php

namespace App\Site\Http\Middleware;

class TryAuthenticate extends Authenticate
{
    protected function unauthenticated($request, array $guards)
    {
    }
}
