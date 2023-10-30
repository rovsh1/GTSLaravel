<?php

declare(strict_types=1);

namespace Module\Booking\Infrastructure\Order\Models;

enum OrderStatusEnum: int
{
    case NEW = 1;
}
