<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Booking;

use Module\Booking\Order\Application\UseCase\GetActiveOrders;
use Module\Booking\Order\Application\UseCase\GetOrder;

class OrderAdapter
{
    public function getActiveOrders(int|null $clientId): array
    {
        return app(GetActiveOrders::class)->execute($clientId);
    }

    public function findOrder(int $id): mixed
    {
        return app(GetOrder::class)->execute($id);
    }
}
