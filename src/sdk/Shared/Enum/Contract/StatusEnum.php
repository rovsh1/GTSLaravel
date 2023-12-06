<?php

namespace Sdk\Shared\Enum\Contract;

enum StatusEnum: int
{
    case ACTIVE = 1;
    case INACTIVE = 2;
    case ARCHIVE = 3;
}
