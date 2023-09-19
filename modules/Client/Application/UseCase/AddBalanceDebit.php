<?php

declare(strict_types=1);

namespace Module\Client\Application\UseCase;

use Module\Client\Application\Dto\AddBalanceDto;
use Module\Client\Domain\Account\Repository\AccountRepositoryInterface;
use Module\Client\Domain\Account\Repository\BalanceRepositoryInterface;
use Module\Client\Domain\Account\ValueObject\AccountId;
use Module\Client\Domain\Account\ValueObject\Debit;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class AddBalanceDebit implements UseCaseInterface
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

        $this->balanceRepository->add(
            accountId: $account->id(),
            sum: new Debit($requestDto->sum),
            context: $requestDto->context
        );
    }
}
