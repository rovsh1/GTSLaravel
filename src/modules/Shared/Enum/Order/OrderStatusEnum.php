<?php

namespace Module\Shared\Enum\Order;

enum OrderStatusEnum: int
{
    case IN_PROGRESS = 1;
    case WAITING_INVOICE = 2;
    case INVOICED = 3;
    case PARTIAL_PAID = 4;
    case PAID = 5;
    case CANCELLED = 6;
    case REFUND_FEE = 7;
    case REFUND_NO_FEE = 8;
}
