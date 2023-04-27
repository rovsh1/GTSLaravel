<?php

namespace Module\Hotel\Domain\Repository;

use Carbon\CarbonPeriod;
use Module\Hotel\Domain\Entity\RoomQuota;

interface RoomQuotaRepositoryInterface
{
    /**
     * @param int $roomId
     * @param CarbonPeriod $period
     * @return RoomQuota[]
     */
    public function get(int $roomId, CarbonPeriod $period): array;

    public function updateRoomQuota(int $roomId, CarbonPeriod $period, int $quota);

    public function closeRoomQuota(int $roomId, CarbonPeriod $period);

    public function openRoomQuota(int $roomId, CarbonPeriod $period);
}
