<?php

declare(strict_types=1);

namespace Module\Client\Domain\Account;

use Module\Client\Domain\Account\ValueObject\AccountId;
use Module\Client\Domain\Account\ValueObject\BalanceSum;
use Sdk\Module\Foundation\Domain\Entity\AbstractAggregateRoot;

final class Account extends AbstractAggregateRoot
{
    public function __construct(
        private readonly AccountId $id,
        private readonly BalanceSum $balanceSum,
    ) {
    }

    public function id(): AccountId
    {
        return $this->id;
    }

    public function balanceSum(): BalanceSum
    {
        return $this->balanceSum;
    }
}