<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Domain\ValueObject\Details\AdditionalInfo;

enum ExternalNumberTypeEnum: int
{
    case GOTOSTANS = 1;
    case HOTEL = 2;
    case FIO = 3;
}
