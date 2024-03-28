<?php

declare(strict_types=1);

namespace Pkg\Supplier\Traveline\Adapters;

use Carbon\CarbonPeriod;
use Module\Hotel\Moderation\Application\Dto\HotelDto;
use Module\Hotel\Moderation\Application\Dto\RoomDto;
use Module\Hotel\Moderation\Application\UseCase\FindHotelById;
use Module\Hotel\Moderation\Application\UseCase\GetRooms;
use Module\Hotel\Moderation\Application\UseCase\Price\SetDatePeriodPrice;

class HotelAdapter
{
    public function getHotelById(int $hotelId): HotelDto
    {
        return app(FindHotelById::class)->execute($hotelId);
    }

    /**
     * @param int $hotelId
     * @return RoomDto[]
     */
    public function getRoomsAndRatePlans(int $hotelId): array
    {
        return app(GetRooms::class)->execute($hotelId);
    }

    public function updateRoomPrice(
        CarbonPeriod $period,
        int $roomId,
        int $rateId,
        int $guestsCount,
        bool $isResident,
        string $currencyCode,
        float $price
    ): void {
        app(SetDatePeriodPrice::class)->execute(
            $period,
            $roomId,
            $rateId,
            $guestsCount,
            $isResident,
            $currencyCode,
            $price
        );
    }
}
