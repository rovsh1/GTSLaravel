<?php

namespace Module\Booking\Common\Domain\ValueObject;

enum BookingStatusEnum: int
{
    case DRAFT = 0;
    case CREATED = 1;
    case PROCESSING = 2;
    case CANCELLED = 3;
    case CONFIRMED = 4;
    case NOT_CONFIRMED = 16;
    case CANCELLED_NO_FEE = 9;
    case CANCELLED_FEE = 10;
    case WAITING_CONFIRMATION = 13;
    case WAITING_CANCELLATION = 14;
    case WAITING_PROCESSING = 15;
    case DELETED = 17;
}
