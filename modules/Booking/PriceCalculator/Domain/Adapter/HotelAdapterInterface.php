<?php

namespace Module\Booking\PriceCalculator\Domain\Adapter;

use Carbon\CarbonInterface;
use Module\Hotel\Application\Dto\MarkupSettingsDto;

interface HotelAdapterInterface
{
    public function getRoomPrice(
        int $roomId,
        int $rateId,
        bool $isResident,
        int $guestsCount,
        int $currencyId,
        CarbonInterface $date
    ): ?float;

    public function getMarkupSettings(int $hotelId): MarkupSettingsDto;
}
