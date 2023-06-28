<?php

namespace Module\Booking\PriceCalculator\Domain\Adapter;

use Carbon\CarbonInterface;
use Module\Hotel\Application\Dto\HotelDto;
use Module\Hotel\Application\Dto\MarkupSettingsDto;
use Module\Shared\Enum\CurrencyEnum;

interface HotelAdapterInterface
{
    public function findById(int $id): ?HotelDto;

    public function getRoomPrice(
        int $roomId,
        int $rateId,
        bool $isResident,
        int $guestsCount,
        CarbonInterface $date
    ): ?float;

    public function getMarkupSettings(int $hotelId): MarkupSettingsDto;
}
