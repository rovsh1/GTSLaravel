<?php

declare(strict_types=1);

namespace Module\Shared\Contracts\Repository;

interface CanTransactionInterface
{
    public function startTransaction(): void;

    public function commitTransaction(): void;

    public function rollBackTransaction(): void;
}
