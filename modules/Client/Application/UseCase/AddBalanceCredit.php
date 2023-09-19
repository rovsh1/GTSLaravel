<?php

declare(strict_types=1);

namespace Module\Client\Application\UseCase;

use Module\Client\Application\Dto\AddBalanceDto;
use Module\Client\Domain\Account\Repository\AccountRepositoryInterface;
use Module\Client\Domain\Account\Repository\BalanceRepositoryInterface;
use Module\Client\Domain\Account\ValueObject\AccountId;
use Module\Client\Domain\Account\ValueObject\Credit;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class AddBalanceCredit implements UseCaseInterface
{
    public function __construct(
        private readonly AccountRepositoryInterface $accountRepository,
        private readonly BalanceRepositoryInterface $balanceRepository,
    ) {
    }

    public function execute(AddBalanceDto $requestDto): void
    {
        $account = $this->accountRepository->find(new AccountId($requestDto->clientId));
        if (!$account) {
            throw new \Exception('Account not found');
        }

        $sum = new Credit($requestDto->sum);

        if ($account->balanceSum()->value() < $sum->value()) {
            throw new \Exception('Balance sum');
        }

        $this->balanceRepository->add(
            accountId: $account->id(),
            sum: $sum,
            context: $requestDto->context
        );
    }
}
