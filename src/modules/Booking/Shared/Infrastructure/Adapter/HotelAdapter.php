<?php

namespace Module\Booking\Shared\Infrastructure\Adapter;

use Module\Booking\Shared\Domain\Booking\Adapter\HotelAdapterInterface;
use Module\Hotel\Moderation\Application\Dto\HotelDto;
use Module\Hotel\Moderation\Application\Dto\MarkupSettingsDto;
use Module\Hotel\Moderation\Application\Dto\PriceRateDto;
use Module\Hotel\Moderation\Application\UseCase\FindHotelById;
use Module\Hotel\Moderation\Application\UseCase\GetMarkupSettings;
use Module\Hotel\Moderation\Application\UseCase\Price\GetPriceRates;

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

    /**
     * @param int $hotelId
     * @return PriceRateDto[]
     */
    public function getHotelRates(int $hotelId): array
    {
        return app(GetPriceRates::class)->execute($hotelId);
    }
}
