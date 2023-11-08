<?php

namespace Module\Client\Domain\Invoice\Adapter;

use Module\Booking\Moderation\Application\Dto\OrderDto;

interface OrderAdapterInterface
{
    public function find(int $orderId): OrderDto;
}
