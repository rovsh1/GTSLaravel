<?php

namespace Sdk\Module\Bus\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;

class UseDatabaseTransactionMiddleware
{
    public function handle($command, Closure $next)
    {
        return DB::transaction(function () use ($command, $next) {
            return $next($command);
        });
    }
}
