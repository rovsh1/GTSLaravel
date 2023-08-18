<?php

declare(strict_types=1);

namespace Module\Shared\Domain\Service;

interface SafeExecutorInterface
{
    public function execute(\Closure $closure, int $attempts = 1): void;
}
