<?php

namespace Module\Booking\Shared\Infrastructure\HotelBooking\Adapter;

use Carbon\CarbonPeriod;
use Module\Booking\Shared\Domain\Booking\Adapter\HotelRoomQuotaAdapterInterface;
use Module\Hotel\Moderation\Application\Response\RoomDto;
use Module\Hotel\Quotation\Application\UseCase\GetAvailableQuotas;

class HotelRoomQuotaAdapter implements HotelRoomQuotaAdapterInterface
{
    /**
     * @param int $hotelId
     * @param CarbonPeriod $period
     * @param int|null $roomId
     * @return array<int, RoomDto>
     */
    public function getAvailable(int $hotelId, CarbonPeriod $period, ?int $roomId = null): array
    {
        return app(GetAvailableQuotas::class)->execute($hotelId, $period, $roomId);
    }

}
