<?php

namespace GTS\Reservation\Domain\ValueObject;

enum ReservationStatusEnum: int
{
    case DRAFT = 0;
    case CREATED = 1;
    case PROCESSING = 2;
    case CANCELLED = 3;
    case CONFIRMED = 4;
    case NOT_CONFIRMED = 16;
    case INVOICED = 6;
    case PAID = 7;
    case PARTIALLY_PAID = 8;
    case CANCELLED_NO_FEE = 9;
    case CANCELLED_FEE = 10;
    case REFUND_NO_FEE = 11;
    case REFUND_FEE = 12;
    //case WAITING_CONFIRMATION = 13;
    case REGISTERED = 13;
    case WAITING_CANCELLATION = 14;
    case WAITING_PROCESSING = 15;
}
