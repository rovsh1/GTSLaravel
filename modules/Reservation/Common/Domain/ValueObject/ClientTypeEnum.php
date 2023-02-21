<?php

namespace Module\Reservation\Common\Domain\ValueObject;

enum ClientTypeEnum: int
{
    case CUSTOMER = 1;
    case CLIENT = 2;
    case HOTEL = 3;
}
