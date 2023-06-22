<?php

namespace Module\Booking\PriceCalculator\Infrastructure\Adapter;

use DateTime;
use Module\Booking\PriceCalculator\Domain\Adapter\HotelAdapterInterface;
use Module\Hotel\Application\Dto\MarkupSettingsDto;
use Module\Hotel\Application\UseCase\GetHotelById;
use Module\Hotel\Application\UseCase\GetMarkupSettings;

class HotelAdapter implements HotelAdapterInterface
{
    public function findById(int $id): mixed
    {
        return app(GetHotelById::class)->execute($id);
    }

    public function getMarkupSettings(int $hotelId): MarkupSettingsDto
    {
        return app(GetMarkupSettings::class)->execute($hotelId);
    }

    public function getRoomPrice(int $roomId, DateTime $date): ?float
    {
        return null;
    }
}
