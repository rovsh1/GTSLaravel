<?php

declare(strict_types=1);

namespace Module\Shared\Enum\Booking;

enum QuotaProcessingMethodEnum: int
{
    case REQUEST = 1;
    case QUOTE = 2;
    case SITE = 3;
}
