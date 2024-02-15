<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\Order;

use Module\Booking\Shared\Domain\Order\DbContext\OrderDbContextInterface;
use Module\Booking\Shared\Domain\Order\Repository\OrderRepositoryInterface;
use Sdk\Booking\ValueObject\OrderId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class UpdateExternalId implements UseCaseInterface
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly OrderDbContextInterface $dbContext,
    ) {}

    public function execute(int $id, ?string $externalId): void
    {
        $order = $this->orderRepository->findOrFail(new OrderId($id));
        $order->setExternalId($externalId);
        $this->dbContext->store($order);
    }
}
