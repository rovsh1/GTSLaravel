<?php

namespace Module\Hotel\Infrastructure\Models\Room;

enum PriceTypeEnum: int
{
    case Resident = 1;
    case Nonresident = 2;
}
