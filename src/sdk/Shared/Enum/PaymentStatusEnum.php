<?php

namespace Sdk\Shared\Enum;

enum PaymentStatusEnum: int
{
    case NOT_PAID = 1;
    case PARTIAL_PAID = 2;
    case PAID = 3;
    case ARCHIVE = 4;
}
