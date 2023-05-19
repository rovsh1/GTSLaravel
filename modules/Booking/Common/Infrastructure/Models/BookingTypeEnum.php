<?php

declare(strict_types=1);

namespace Module\Booking\Common\Infrastructure\Models;

enum BookingTypeEnum: int
{
    case HOTEL = 1;
    case AIRPORT = 2;
    case TRANSPORT = 3;
}
