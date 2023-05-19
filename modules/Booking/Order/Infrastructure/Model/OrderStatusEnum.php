<?php

declare(strict_types=1);

namespace Module\Booking\Order\Infrastructure\Model;

enum OrderStatusEnum: int
{
    case NEW = 1;
}
