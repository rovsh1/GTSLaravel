<?php

namespace Module\Hotel\Moderation\Infrastructure\Models\Room;

enum QuotaStatusEnum: int
{
    case CLOSE = 0;
    case OPEN = 1;
}
