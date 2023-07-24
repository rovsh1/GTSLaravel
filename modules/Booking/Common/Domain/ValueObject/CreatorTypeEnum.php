<?php

namespace Module\Booking\Common\Domain\ValueObject;

enum CreatorTypeEnum: int
{
    case CUSTOMER = 1;
    case ADMINISTRATOR = 2;
    case HOTEL = 3;
}