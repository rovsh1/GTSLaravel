<?php

declare(strict_types=1);

namespace Module\Client\Infrastructure\Repository;

use Module\Client\Domain\Account\Account;
use Module\Client\Domain\Account\Repository\AccountRepositoryInterface;
use Module\Client\Domain\Account\ValueObject\AccountId;
use Module\Client\Domain\Account\ValueObject\BalanceSum;
use Module\Client\Infrastructure\Models\Balance;
use Module\Client\Infrastructure\Models\Client as Model;

class AccountRepository implements AccountRepositoryInterface
{
    public function find(AccountId $id): ?Account
    {
        $model = Model::find($id);
        if ($model === null) {
            return null;
        }

        return new Account(
            id: new AccountId($model->id),
            balanceSum: new BalanceSum(Balance::clientBalanceSum($model->id))
        );
    }
}
