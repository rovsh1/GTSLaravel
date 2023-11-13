<?php

declare(strict_types=1);

namespace Module\Shared\Infrastructure\Service;

use Illuminate\Support\Facades\DB;
use Module\Shared\Contracts\Service\SafeExecutorInterface;

class TransactionalExecutor implements SafeExecutorInterface
{
    public function execute(\Closure $closure, int $attempts = 1): mixed
    {
        return DB::transaction($closure, $attempts);
    }
}
