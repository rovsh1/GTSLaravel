<?php

namespace Module\Booking\Shared\Infrastructure\HotelBooking\Adapter;

use Carbon\CarbonInterface;
use Module\Booking\Shared\Domain\Booking\Adapter\HotelAdapterInterface;
use Module\Hotel\Moderation\Application\Response\HotelDto;
use Module\Hotel\Moderation\Application\Response\MarkupSettingsDto;
use Module\Hotel\Moderation\Application\ResponseDto\PriceRateDto;
use Module\Hotel\Moderation\Application\UseCase\FindHotelById;
use Module\Hotel\Moderation\Application\UseCase\GetMarkupSettings;
use Module\Hotel\Moderation\Application\UseCase\Price\GetPriceRates;
use Module\Hotel\Pricing\Application\UseCase\GetHotelRoomBasePrice;

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
        return app(GetHotelRoomBasePrice::class)->execute($roomId, $rateId, $isResident, $guestsCount, $date);
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
