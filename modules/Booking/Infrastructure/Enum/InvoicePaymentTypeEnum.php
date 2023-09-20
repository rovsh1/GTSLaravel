<?php

namespace Module\Booking\Infrastructure\Enum;

enum InvoicePaymentTypeEnum: int
{
    case CLIENT = 1;
    case SUPPLIER = 2;
}
