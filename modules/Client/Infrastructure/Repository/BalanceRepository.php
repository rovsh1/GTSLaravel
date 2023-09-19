<?php

declare(strict_types=1);

namespace Module\Client\Infrastructure\Repository;

use Module\Client\Domain\Account\Repository\BalanceRepositoryInterface;
use Module\Client\Domain\Account\ValueObject\AccountId;
use Module\Client\Domain\Account\ValueObject\Credit;
use Module\Client\Domain\Account\ValueObject\Debit;
use Module\Client\Infrastructure\Models\Balance;

class BalanceRepository implements BalanceRepositoryInterface
{
    public function add(AccountId $accountId, Debit|Credit $sum, array $context): void
    {
        Balance::create([
            'client_id' => $accountId->value(),
            'debit' => $sum instanceof Debit ? $sum->value() : 0,
            'credit' => $sum instanceof Credit ? $sum->value() : 0,
            'context' => $context,
        ]);
    }
}
