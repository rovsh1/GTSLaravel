<?php

declare(strict_types=1);

namespace Sdk\Shared\Enum\Client;

enum StatusEnum: int
{
    case ACTIVE = 1;
    case BLOCKED = 2;
    case ARCHIVE = 3;
}
