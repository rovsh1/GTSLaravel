<?php

namespace Module\Integration\Traveline\Infrastructure\Models\Room;

enum DayPriceTypeEnum: string
{
    case Brutto = 'brutto';
    case Netto = 'netto';
    case NettoHotel = 'netto_hotel';
}
