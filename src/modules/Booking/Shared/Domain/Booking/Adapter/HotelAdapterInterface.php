<?php

namespace Module\Booking\Shared\Domain\Booking\Adapter;

use Module\Hotel\Moderation\Application\Dto\HotelDto;
use Module\Hotel\Moderation\Application\Dto\MarkupSettingsDto;

interface HotelAdapterInterface
{
    public function findById(int $id): ?HotelDto;

    public function getMarkupSettings(int $hotelId): MarkupSettingsDto;

    public function getHotelRates(int $hotelId): array;
}
