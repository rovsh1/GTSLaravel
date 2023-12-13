<?php

namespace Module\Booking\Shared\Infrastructure\Service\UnitOfWork;

use Module\Booking\Shared\Domain\Order\DbContext\OrderDbContextInterface;
use Sdk\Booking\ValueObject\OrderId;

class OrderCommiter
{
    /**
     * @var array<int, bool> $updatedEntitiesFlags
     */
    private array $updatedEntitiesFlags = [];

    public function __construct(
        private readonly OrderDbContextInterface $orderDbContext,
    ) {}

    public function touch(OrderId $orderId): void
    {
        if (!isset($this->updatedEntitiesFlags[$orderId->value()])) {
            $this->updatedEntitiesFlags[$orderId->value()] = false;
        }
    }

    public function finish(): void
    {
        foreach ($this->updatedEntitiesFlags as $id => $flag) {
            $this->orderDbContext->touch(new OrderId($id));
        }

        $this->updatedEntitiesFlags = [];
    }
}
