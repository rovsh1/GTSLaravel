<?php

declare(strict_types=1);

namespace Module\Booking\Order\Infrastructure\Models;

enum OrderStatusEnum: int
{
    case NEW = 1;
}
