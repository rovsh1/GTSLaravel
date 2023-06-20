<?php

namespace Module\Booking\Order\Domain\Repository;

use Module\Booking\Order\Domain\Entity\Order;

interface OrderRepositoryInterface
{
    public function find(int $orderId): ?Order;
}
