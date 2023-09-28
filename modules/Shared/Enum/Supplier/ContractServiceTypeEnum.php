<?php

declare(strict_types=1);

namespace Module\Shared\Enum\Supplier;

enum ContractServiceTypeEnum: int
{
    case HOTEL = 1;
    case AIRPORT = 2;
    case TRANSFER = 3;
}
