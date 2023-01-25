<?php

namespace GTS\Services\Traveline\Interface\Site\Http\Middleware;

use \GTS\Shared\Interface\Common\Http\Middleware\VerifyCsrfToken as BaseMiddleware;

class VerifyCsrfToken extends BaseMiddleware {

    protected $except = [
        '/integration/*'
    ];

}
