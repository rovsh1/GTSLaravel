<?php

declare(strict_types=1);

namespace Module\Shared\Enum\Client\User;

enum RoleEnum: int
{
    case CUSTOMER = 1;
    case HOTEL = 2;
    case CLIENT = 3;
    case ADMINISTRATOR = 4;
    case DEVELOPER = 0;
}
