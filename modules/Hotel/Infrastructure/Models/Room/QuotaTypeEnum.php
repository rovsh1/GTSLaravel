<?php

namespace Module\Hotel\Infrastructure\Models\Room;

enum QuotaTypeEnum: int
{
    case Open = 1;
    case Close = 2;
}
