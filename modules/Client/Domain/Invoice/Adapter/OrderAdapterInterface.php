<?php

namespace Module\Client\Domain\Invoice\Adapter;

use Module\Booking\Order\Application\Response\OrderDto;

interface OrderAdapterInterface
{
    public function find(int $orderId): OrderDto;
}