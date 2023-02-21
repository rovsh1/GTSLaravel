<?php

namespace Module\Reservation\HotelReservation\Infrastructure\Models;

enum ReservationStatusEnum: int
{
    case Draft = 0;
    case Created = 1;
    case Processing = 2;
    case Cancelled = 3;
    case Confirmed = 4;
    case NotConfirmed = 16;
    case Invoiced = 6;
    case Paid = 7;
    case PartiallyPaid = 8;
    case CancelledNoFee = 9;
    case CancelledFee = 10;
    case RefundNoFee = 11;
    case RefundFee = 12;
    case WaitingConfirmation = 13;
    case WaitingCancellation = 14;
    case WaitingProcessing = 15;
}
