<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Infrastructure\Repository;

use Module\Booking\Shared\Domain\Order\Order;
use Module\Booking\Shared\Domain\Order\Repository\OrderRepositoryInterface;
use Module\Booking\Shared\Infrastructure\DbContext\OrderDbContext;
use Sdk\Booking\ValueObject\OrderId;
use Sdk\Module\Foundation\Exception\EntityNotFoundException;
use Sdk\Shared\Support\RepositoryInstances;

class OrderRepository implements OrderRepositoryInterface
{
    private static RepositoryInstances $instances;

    public function __construct(
        private readonly OrderDbContext $orderDbContext,
    ) {
        self::$instances = new RepositoryInstances();
    }

    public function find(OrderId $id): ?Order
    {
        if (self::$instances->has($id)) {
            return self::$instances->get($id);
        }

        $order = $this->orderDbContext->find($id);
        if ($order === null) {
            return null;
        }

        self::$instances->add($order->id(), $order);

        return $order;
    }

    /**
     * @param int $id
     * @return Order
     * @throws EntityNotFoundException
     */
    public function findOrFail(OrderId $id): Order
    {
        return $this->find($id) ?? throw new EntityNotFoundException("Order[{$id->value()}] not found");
    }

    /**
     * @return Order[]
     */
    public function getActiveOrders(int|null $clientId): array
    {
        return $this->orderDbContext->getActiveOrders($clientId);
    }
}
