<?php

namespace Module\Booking\Infrastructure\HotelBooking\Adapter;

use Carbon\CarbonPeriod;
use Module\Booking\Deprecated\HotelBooking\Adapter\HotelRoomQuotaAdapterInterface;
use Module\Catalog\Application\Admin\Quota\GetAvailable;
use Module\Catalog\Application\Admin\Response\RoomDto;

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
        return app(GetAvailable::class)->execute($hotelId, $period, $roomId);
    }

}
