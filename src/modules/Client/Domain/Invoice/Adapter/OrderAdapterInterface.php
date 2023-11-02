<?php

namespace Module\Client\Domain\Invoice\Adapter;

use Module\Booking\Application\Dto\OrderDto;

interface OrderAdapterInterface
{
    public function find(int $orderId): OrderDto;
}
