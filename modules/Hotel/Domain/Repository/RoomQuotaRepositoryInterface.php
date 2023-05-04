<?php

namespace Module\Hotel\Domain\Repository;

use Carbon\CarbonPeriod;
use Module\Hotel\Domain\Entity\RoomQuota;

interface RoomQuotaRepositoryInterface
{
    /**
     * @param int $hotelId
     * @param CarbonPeriod $period
     * @param int|null $roomId
     * @return RoomQuota[]
     */
    public function get(int $hotelId, CarbonPeriod $period, ?int $roomId = null): array;

    public function updateRoomQuota(int $roomId, CarbonPeriod $period, ?int $quota, ?int $releaseDays = null);

    public function closeRoomQuota(int $roomId, CarbonPeriod $period);

    public function openRoomQuota(int $roomId, CarbonPeriod $period);
}
