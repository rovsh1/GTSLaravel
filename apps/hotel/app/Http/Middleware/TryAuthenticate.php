<?php

namespace App\Hotel\Http\Middleware;

class TryAuthenticate extends Authenticate
{
    protected function unauthenticated($request, array $guards) {}
}
