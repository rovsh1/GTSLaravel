<?php

declare(strict_types=1);

namespace Module\Client\Application\UseCase;

use Module\Client\Domain\Account\Repository\AccountRepositoryInterface;
use Module\Client\Domain\Account\ValueObject\AccountId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetBalanceSum implements UseCaseInterface
{
    public function __construct(
        private readonly AccountRepositoryInterface $accountRepository,
    ) {
    }

    public function execute(int $clientId): float
    {
        $account = $this->accountRepository->find(new AccountId($clientId));
        if (!$account) {
            throw new \Exception('Account not found');
        }

        return $account->balanceSum()->value();
    }
}
