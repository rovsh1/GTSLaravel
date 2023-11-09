<?php

declare(strict_types=1);

namespace Module\Administrator\Infrastructure\Repository;

use Module\Administrator\Domain\Repository\OrderAdministratorRepositoryInterface;
use Module\Administrator\Domain\ValueObject\AdministratorId;
use Module\Booking\Shared\Domain\Order\ValueObject\OrderId;

class OrderAdministratorRepository implements OrderAdministratorRepositoryInterface
{
    public function set(OrderId $orderId, AdministratorId $administratorId): void
    {
        \DB::table('administrator_orders')->updateOrInsert(
            ['order_id' => $orderId->value()],
            ['order_id' => $orderId->value(), 'administrator_id' => $administratorId->value()],
        );
    }
}
