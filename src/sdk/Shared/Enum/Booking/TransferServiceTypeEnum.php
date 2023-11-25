<?php

namespace Sdk\Shared\Enum\Booking;

enum TransferServiceTypeEnum: int
{
    case CAR_RENT = 1;
    case TRANSFER_TO_RAILWAY = 2;
    case TRANSFER_FROM_RAILWAY = 3;
    case TRANSFER_TO_AIRPORT = 4;
    case TRANSFER_FROM_AIRPORT = 5;
}
