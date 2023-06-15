<?php

namespace Module\Shared\Enum\Booking;

enum ServiceTypeEnum: int
{
    case CAR_RENT = 1;
    case TRANSFER_TO_RAILWAY = 2;
    case TRANSFER_FROM_RAILWAY = 3;
    case TRANSFER_TO_AIRPORT = 4;
    case TRANSFER_FROM_AIRPORT = 5;
    case MEETING_IN_AIRPORT = 6;
    case SEEING_IN_AIRPORT = 7;
}
