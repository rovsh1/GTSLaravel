<?php

declare(strict_types=1);

namespace Module\Administrator\Infrastructure\Repository;

use Illuminate\Support\Facades\DB;
use Module\Administrator\Domain\Repository\OrderAdministratorRepositoryInterface;
use Module\Administrator\Domain\ValueObject\AdministratorId;
use Sdk\Booking\ValueObject\OrderId;

class OrderAdministratorRepository implements OrderAdministratorRepositoryInterface
{
    public function set(OrderId $orderId, AdministratorId $administratorId): void
    {
        DB::table('administrator_orders')->updateOrInsert(
            ['order_id' => $orderId->value()],
            ['order_id' => $orderId->value(), 'administrator_id' => $administratorId->value()],
        );
    }
}
