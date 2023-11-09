<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\Order;

use Module\Booking\Moderation\Application\Factory\OrderDtoFactory;
use Module\Booking\Shared\Domain\Order\Repository\OrderRepositoryInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class GetActiveOrders implements UseCaseInterface
{
    public function __construct(
        private readonly OrderRepositoryInterface $repository,
        private readonly OrderDtoFactory $factory,
    ) {}

    public function execute(int|null $clientId, bool $isOnlyWaitingInvoice = false): array
    {
        $orders = $this->repository->getActiveOrders($clientId, $isOnlyWaitingInvoice);

        return $this->factory->createCollection($orders);
    }
}
