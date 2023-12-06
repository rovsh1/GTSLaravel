<?php

declare(strict_types=1);

namespace Sdk\Booking\Enum;

enum QuotaChangeTypeEnum: int
{
    case RESERVED = 1;
    case BOOKED = 2;
}
