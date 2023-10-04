<?php

namespace Module\Client\Domain\Invoice\Adapter;

use Module\Booking\Application\Order\Response\OrderDto;

interface OrderAdapterInterface
{
    public function find(int $orderId): OrderDto;
}
