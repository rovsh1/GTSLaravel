<?php

namespace Module\Hotel\Domain\Repository;

use Carbon\CarbonPeriod;

interface RoomQuotaRepositoryInterface
{
    public function updateRoomQuota(int $roomId, CarbonPeriod $period, int $quota);

    public function updateRoomReleaseDays(int $roomId, CarbonPeriod $period, int $releaseDays): void;

    public function closeRoomQuota(int $roomId, CarbonPeriod $period);

    public function openRoomQuota(int $roomId, CarbonPeriod $period);
}
