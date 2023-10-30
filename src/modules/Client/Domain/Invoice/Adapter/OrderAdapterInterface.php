<?php

namespace Module\Client\Domain\Invoice\Adapter;

use Module\Booking\Application\Admin\Order\Response\OrderDto;

interface OrderAdapterInterface
{
    public function find(int $orderId): OrderDto;
}
