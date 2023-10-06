<?php

namespace Module\Booking\Domain\Shared\Entity;

enum ClientTypeEnum: int
{
    case CUSTOMER = 1;
    case CLIENT = 2;
    case HOTEL = 3;
}
