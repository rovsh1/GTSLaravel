<?php

namespace Module\Booking\PriceCalculator\Domain\Adapter;

use DateTime;
use Module\Hotel\Application\Dto\MarkupSettingsDto;

interface HotelAdapterInterface
{
    public function getRoomPrice(int $roomId, DateTime $date): ?float;

    public function getMarkupSettings(int $hotelId): MarkupSettingsDto;
}
