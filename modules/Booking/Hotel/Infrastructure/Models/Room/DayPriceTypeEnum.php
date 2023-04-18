<?php

namespace Module\Booking\Hotel\Infrastructure\Models\Room;

enum DayPriceTypeEnum: string
{
    case Brutto = 'brutto';
    case Netto = 'netto';
    case NettoHotel = 'netto_hotel';
}
