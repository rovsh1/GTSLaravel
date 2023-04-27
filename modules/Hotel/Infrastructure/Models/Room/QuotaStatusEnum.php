<?php

namespace Module\Hotel\Infrastructure\Models\Room;

enum QuotaStatusEnum: int
{
    case Close = 0;
    case Open = 1;
}
