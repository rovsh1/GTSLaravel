<?php

declare(strict_types=1);

namespace Module\Booking\Order\Infrastructure\Repository;

use Module\Booking\Order\Domain\Entity\Order;
use Module\Booking\Order\Domain\Factory\OrderFactory;
use Module\Booking\Order\Domain\Repository\OrderRepositoryInterface;
use Module\Booking\Order\Infrastructure\Models\Order as Model;

class OrderRepository implements OrderRepositoryInterface
{
    public function __construct(
        private readonly OrderFactory $factory
    ) {}

    public function find(int $id): ?Order
    {
        $model = Model::find($id);
        if (!$model) {
            return null;
        }

        return $this->factory->createFrom($model);
    }

    /**
     * @return Order[]
     */
    public function getActiveOrders(): array
    {
        $models = Model::query()->get()->all();

        return $this->factory->createCollectionFrom($models);
    }
}
