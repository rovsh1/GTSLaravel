<?php

declare(strict_types=1);

namespace Sdk\Shared\Enum\Booking;

enum QuotaChangeTypeEnum: int
{
    case RESERVED = 1;
    case BOOKED = 2;
}
