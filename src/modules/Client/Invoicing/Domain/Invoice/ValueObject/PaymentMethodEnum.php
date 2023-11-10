<?php

namespace Module\Client\Invoicing\Domain\Invoice\ValueObject;

enum PaymentMethodEnum: int
{
    case NOT_PAID = 1;
    case PARTIAL_PAID = 2;
    case PAID = 3;
}
