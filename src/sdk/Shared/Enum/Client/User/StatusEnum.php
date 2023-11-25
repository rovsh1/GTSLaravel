<?php

declare(strict_types=1);

namespace Sdk\Shared\Enum\Client\User;

enum StatusEnum: int
{
    case UNCONFIRMED = 0;
    case ACTIVE = 1;
    case BLOCKED = 2;
}
