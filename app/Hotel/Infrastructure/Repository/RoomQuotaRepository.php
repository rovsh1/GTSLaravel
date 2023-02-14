<?php

namespace GTS\Hotel\Infrastructure\Repository;

use Carbon\CarbonPeriod;

use GTS\Hotel\Domain\Repository\RoomQuotaRepositoryInterface;
use GTS\Hotel\Infrastructure\Models\Room\Quota as EloquentQuota;

class RoomQuotaRepository implements RoomQuotaRepositoryInterface
{
    public function updateRoomQuota(int $roomId, CarbonPeriod $period, int $quota): void
    {
        EloquentQuota::whereRoomId($roomId)
            ->where('date', '>=', $period->getStartDate())
            ->where('date', '<', $period->getEndDate())
            ->update(['count_available' => $quota]);
    }
}
