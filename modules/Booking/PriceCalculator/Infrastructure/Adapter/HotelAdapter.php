<?php

namespace Module\Booking\PriceCalculator\Infrastructure\Adapter;

use Carbon\CarbonInterface;
use Module\Booking\PriceCalculator\Domain\Adapter\HotelAdapterInterface;
use Module\Hotel\Application\Response\HotelDto;
use Module\Hotel\Application\Response\MarkupSettingsDto;
use Module\Hotel\Application\UseCase\FindHotelById;
use Module\Hotel\Application\UseCase\GetMarkupSettings;
use Module\Hotel\Application\UseCase\Price\FindRoomPrice;

class HotelAdapter implements HotelAdapterInterface
{
    public function findById(int $id): ?HotelDto
    {
        return app(FindHotelById::class)->execute($id);
    }

    public function getMarkupSettings(int $hotelId): MarkupSettingsDto
    {
        return app(GetMarkupSettings::class)->execute($hotelId);
    }

    public function getRoomPrice(
        int $roomId,
        int $rateId,
        bool $isResident,
        int $guestsCount,
        CarbonInterface $date
    ): ?float {
        $roomPriceDto = app(FindRoomPrice::class)->execute($roomId, $rateId, $isResident, $guestsCount, $date);

        return $roomPriceDto?->price;
    }
}
