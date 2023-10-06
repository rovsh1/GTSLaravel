<?php

namespace Module\Shared\Enum;

enum ServiceTypeEnum: int
{
    case MEETING_IN_AIRPORT = 1;
    case SEEING_IN_AIRPORT = 2;
    case CAR_RENT = 3;
    case TRANSFER_TO_RAILWAY = 4;
    case TRANSFER_FROM_RAILWAY = 5;
    case TRANSFER_TO_AIRPORT = 6;
    case TRANSFER_FROM_AIRPORT = 7;
}
