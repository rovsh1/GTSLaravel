<?php

namespace Module\Booking\Shared\Infrastructure\HotelBooking\Adapter;

use Carbon\CarbonInterface;
use Module\Booking\Shared\Domain\Booking\Adapter\HotelAdapterInterface;
use Module\Hotel\Moderation\Application\Admin\Response\HotelDto;
use Module\Hotel\Moderation\Application\Admin\Response\MarkupSettingsDto;
use Module\Hotel\Moderation\Application\Admin\Response\RoomMarkupsDto;
use Module\Hotel\Moderation\Application\Admin\ResponseDto\PriceRateDto;
use Module\Hotel\Moderation\Application\Admin\UseCase\FindHotelById;
use Module\Hotel\Moderation\Application\Admin\UseCase\GetMarkupSettings;
use Module\Hotel\Moderation\Application\Admin\UseCase\GetRoomMarkups;
use Module\Hotel\Moderation\Application\Admin\UseCase\Price\GetPriceRates;
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

    public function getRoomMarkupSettings(int $roomId): ?RoomMarkupsDto
    {
        return app(GetRoomMarkups::class)->execute($roomId);
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
