<?php

namespace Module\Booking\Shared\Domain\Shared\ValueObject;

enum CreatorTypeEnum: int
{
    case CUSTOMER = 1;
    case ADMINISTRATOR = 2;
    case HOTEL = 3;
}
