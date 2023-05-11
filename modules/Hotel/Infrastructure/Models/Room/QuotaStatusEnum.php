<?php

namespace Module\Hotel\Infrastructure\Models\Room;

enum QuotaStatusEnum: int
{
    case CLOSE = 0;
    case OPEN = 1;
}
