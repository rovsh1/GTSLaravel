<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Domain\ValueObject;

enum QuotaProcessingMethodEnum: int
{
    case REQUEST = 1;
    case QUOTE = 2;
}
