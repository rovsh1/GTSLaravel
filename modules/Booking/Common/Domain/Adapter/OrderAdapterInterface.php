<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Adapter;

interface OrderAdapterInterface
{
    public function createOrder(int $clientId): int;
}
