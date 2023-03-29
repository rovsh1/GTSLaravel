<?php

namespace Module\HotelOld\Infrastructure\Models\Room;

enum PriceTypeEnum: int
{
    case Resident = 1;
    case Nonresident = 2;
}
