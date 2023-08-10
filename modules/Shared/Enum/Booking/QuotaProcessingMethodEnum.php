<?php

declare(strict_types=1);

namespace Module\Shared\Enum\Booking;

enum QuotaProcessingMethodEnum: int
{
    case REQUEST = 1;
    case QUOTA = 2;
    case SITE = 3;
}
