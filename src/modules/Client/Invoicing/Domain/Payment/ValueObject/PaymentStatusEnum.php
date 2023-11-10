<?php

namespace Module\Client\Invoicing\Domain\Payment\ValueObject;

enum PaymentStatusEnum: int
{
    case NOT_PAID = 1;
    case PARTIAL_PAID = 2;
    case PAID = 3;
    case ARCHIVE = 4;
}
