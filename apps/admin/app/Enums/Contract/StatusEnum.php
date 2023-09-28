<?php

namespace App\Admin\Enums\Contract;

enum StatusEnum: int
{
    case ACTIVE = 1;
    case INACTIVE = 2;
    case ARCHIVE = 3;
}
