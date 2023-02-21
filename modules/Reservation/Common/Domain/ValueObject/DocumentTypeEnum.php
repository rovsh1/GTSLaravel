<?php

namespace Module\Reservation\Common\Domain\ValueObject;

enum DocumentTypeEnum: int
{
    case RESERVATION = 1;
    case CHANGE = 2;
    case CANCELLATION = 3;
    case VOUCHER = 4;
}
