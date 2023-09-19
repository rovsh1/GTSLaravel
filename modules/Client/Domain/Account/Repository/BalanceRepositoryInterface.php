<?php

namespace Module\Client\Domain\Account\Repository;

use Module\Client\Domain\Account\ValueObject\AccountId;
use Module\Client\Domain\Account\ValueObject\Credit;
use Module\Client\Domain\Account\ValueObject\Debit;

interface BalanceRepositoryInterface
{
    public function add(AccountId $accountId, Debit|Credit $sum, array $context): void;

//    public function query();
}