<?php

declare(strict_types=1);

namespace Module\Administrator\Domain\Repository;

use Module\Administrator\Domain\ValueObject\AdministratorId;
use Sdk\Booking\ValueObject\OrderId;

interface OrderAdministratorRepositoryInterface
{
    public function set(OrderId $orderId, AdministratorId $administratorId): void;
}
