<?php

namespace Module\Booking\Shared\Domain\Booking\Adapter;

use Carbon\CarbonInterface;
use Module\Hotel\Moderation\Application\Admin\Response\HotelDto;
use Module\Hotel\Moderation\Application\Admin\Response\MarkupSettingsDto;
use Module\Hotel\Moderation\Application\Admin\Response\RoomMarkupsDto;

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

    public function getRoomMarkupSettings(int $roomId): ?RoomMarkupsDto;

    public function getHotelRates(int $hotelId): array;
}
