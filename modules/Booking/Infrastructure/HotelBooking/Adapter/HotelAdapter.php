<?php

namespace Module\Booking\Infrastructure\HotelBooking\Adapter;

use Carbon\CarbonInterface;
use Module\Booking\Domain\HotelBooking\Adapter\HotelAdapterInterface;
use Module\Hotel\Application\Response\HotelDto;
use Module\Hotel\Application\Response\MarkupSettingsDto;
use Module\Hotel\Application\Response\RoomMarkupsDto;
use Module\Hotel\Application\UseCase\FindHotelById;
use Module\Hotel\Application\UseCase\GetMarkupSettings;
use Module\Hotel\Application\UseCase\GetRoomMarkups;
use Module\Pricing\Application\UseCase\HotelRoomBasePriceExists;

class HotelAdapter implements HotelAdapterInterface
{
    public function findById(int $id): ?HotelDto
    {
        return app(FindHotelById::class)->execute($id);
    }

    public function getRoomPrice(
        int $roomId,
        int $rateId,
        bool $isResident,
        int $guestsCount,
        CarbonInterface $date
    ): ?float {
        $roomPriceDto = app(HotelRoomBasePriceExists::class)->execute($roomId, $rateId, $isResident, $guestsCount, $date);

        return $roomPriceDto?->price;
    }

    public function getMarkupSettings(int $hotelId): MarkupSettingsDto
    {
        return app(GetMarkupSettings::class)->execute($hotelId);
    }

    public function getRoomMarkupSettings(int $roomId): ?RoomMarkupsDto
    {
        return app(GetRoomMarkups::class)->execute($roomId);
    }
}
