<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\UseCase\Order;

use Module\Booking\Shared\Domain\Order\DbContext\OrderDbContextInterface;
use Module\Booking\Shared\Domain\Order\Repository\OrderRepositoryInterface;
use Sdk\Booking\ValueObject\OrderId;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;
use Sdk\Shared\ValueObject\Money;

class UpdateClientPenalty implements UseCaseInterface
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly OrderDbContextInterface $dbContext,
    ) {}

    public function execute(int $id, ?float $clientPenalty): void
    {
        $order = $this->orderRepository->findOrFail(new OrderId($id));
        if ($clientPenalty === null) {
            $order->setClientPenalty($clientPenalty);
        } else {
            $penalty = new Money($order->currency(), $clientPenalty);
            $order->setClientPenalty($penalty);
        }
        $this->dbContext->store($order);
    }
}
