<?php

declare(strict_types=1);

namespace Module\Client\Invoicing\Application\UseCase;

use Module\Client\Invoicing\Application\Factory\OrderDtoFactory;
use Module\Client\Invoicing\Domain\Order\Repository\OrderRepositoryInterface;
use Module\Client\Shared\Domain\ValueObject\PaymentId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetPaymentOrders implements UseCaseInterface
{
    public function __construct(
        private readonly OrderDtoFactory $dtoFactory,
        private readonly OrderRepositoryInterface $repository,
    ) {}

    public function execute(int $paymentId): array
    {
        $orders = $this->repository->getPaymentOrders(new PaymentId($paymentId));

        return $this->dtoFactory->collection($orders);
    }
}
