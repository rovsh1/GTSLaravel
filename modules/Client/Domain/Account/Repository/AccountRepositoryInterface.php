<?php

namespace Module\Client\Domain\Account\Repository;

use Module\Client\Domain\Account\Account;
use Module\Client\Domain\Account\ValueObject\AccountId;

interface AccountRepositoryInterface
{
    public function find(AccountId $id): ?Account;
}