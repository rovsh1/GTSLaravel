<?php

namespace Module\Client\Invoicing\Domain\Invoice\Adapter;

use Module\Booking\Moderation\Application\Dto\OrderDto;

interface OrderAdapterInterface
{
    public function find(int $orderId): OrderDto;
}
