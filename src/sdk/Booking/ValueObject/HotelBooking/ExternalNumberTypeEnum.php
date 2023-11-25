<?php

declare(strict_types=1);

namespace Sdk\Booking\ValueObject\HotelBooking;

enum ExternalNumberTypeEnum: int
{
    case GOTOSTANS = 1;
    case HOTEL = 2;
    case FIO = 3;
}
