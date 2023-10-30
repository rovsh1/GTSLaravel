<?php

declare(strict_types=1);

namespace Module\Shared\Enum\Client;

enum ResidencyEnum: int
{
    case RESIDENT = 1;
    case NONRESIDENT = 2;
    case ALL = 3;
}
