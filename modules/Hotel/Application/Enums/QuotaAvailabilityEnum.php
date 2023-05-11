<?php

namespace Module\Hotel\Application\Enums;

enum QuotaAvailabilityEnum: int
{
    case SOLD = 0;
    case STOPPED = 1;
    case AVAILABLE = 2;
}
