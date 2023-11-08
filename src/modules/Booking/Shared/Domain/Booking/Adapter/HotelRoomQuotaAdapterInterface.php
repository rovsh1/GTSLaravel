<?php

namespace Module\Booking\Shared\Domain\Booking\Adapter;

use Carbon\CarbonPeriod;
use Module\Hotel\Moderation\Application\Admin\Response\RoomDto;

interface HotelRoomQuotaAdapterInterface
{
    /**
     * @param int $hotelId
     * @param CarbonPeriod $period
     * @param int|null $roomId
     * @return array<int, RoomDto>
     */
    public function getAvailable(int $hotelId, CarbonPeriod $period, ?int $roomId = null): array;
}
