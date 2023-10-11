<?php

namespace Module\Booking\Infrastructure\HotelBooking\Adapter;

use Carbon\CarbonInterface;
use Module\Booking\Domain\Booking\Adapter\HotelAdapterInterface;
use Module\Catalog\Application\Admin\Response\HotelDto;
use Module\Catalog\Application\Admin\Response\MarkupSettingsDto;
use Module\Catalog\Application\Admin\Response\RoomMarkupsDto;
use Module\Catalog\Application\Admin\UseCase\FindHotelById;
use Module\Catalog\Application\Admin\UseCase\GetMarkupSettings;
use Module\Catalog\Application\Admin\UseCase\GetRoomMarkups;
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
