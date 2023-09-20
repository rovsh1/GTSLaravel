<?php

namespace Module\Client\Domain\Order\ValueObject;

enum OrderStatusEnum: int
{
    case IN_PROGRESS = 1;
    case WAITING_INVOICE = 2;
    case INVOICED = 3;
    case PARTIAL_PAID = 4;
    case PAID = 5;
}
