<?php

namespace Module\Hotel\Domain\ValueObject;

enum HotelCancelPeriodEnum: int
{
    case FirstNight = 1;
    case FullPeriod = 2;
}
