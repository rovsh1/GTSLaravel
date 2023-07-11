<?php

declare(strict_types=1);

namespace Module\Integration\Traveline\Infrastructure\Models\Legacy;

enum ReservationRequestTypeEnum: int
{
    case REQUEST = 1;
    case QUOTE = 2;
}
