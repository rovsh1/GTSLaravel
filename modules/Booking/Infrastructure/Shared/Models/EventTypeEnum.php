<?php

declare(strict_types=1);

namespace Module\Booking\Infrastructure\Shared\Models;

enum EventTypeEnum: int
{
    case STATUS = 1;
    case REQUEST = 2;
    case OTHER = 3;
}
