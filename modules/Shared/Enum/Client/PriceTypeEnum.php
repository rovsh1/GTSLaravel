<?php

declare(strict_types=1);

namespace Module\Shared\Enum\Client;

enum PriceTypeEnum: int
{
    case RESIDENT = 1;
    case NONRESIDENT = 2;
}
