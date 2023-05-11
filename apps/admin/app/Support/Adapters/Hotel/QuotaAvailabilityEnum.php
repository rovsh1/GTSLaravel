<?php

declare(strict_types=1);

namespace App\Admin\Support\Adapters\Hotel;

enum QuotaAvailabilityEnum: int
{
    case SOLD = 0;
    case STOPPED = 1;
    case AVAILABLE = 2;
}
