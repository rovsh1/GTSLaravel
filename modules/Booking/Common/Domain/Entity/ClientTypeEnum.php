<?php

namespace Module\Booking\Common\Domain\Entity;

enum ClientTypeEnum: int
{
    case CUSTOMER = 1;
    case CLIENT = 2;
    case HOTEL = 3;
}
