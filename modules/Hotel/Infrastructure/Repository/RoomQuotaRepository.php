<?php

namespace Module\Hotel\Infrastructure\Repository;

use Carbon\CarbonPeriod;
use Module\Hotel\Domain\Factory\RoomQuotaFactory;
use Module\Hotel\Domain\Repository\RoomQuotaRepositoryInterface;
use Module\Hotel\Infrastructure\Models\Room\Quota as EloquentQuota;
use Module\Hotel\Infrastructure\Models\Room\QuotaStatusEnum;

class RoomQuotaRepository implements RoomQuotaRepositoryInterface
{
    public function get(int $roomId, CarbonPeriod $period): array
    {
        $models = EloquentQuota::whereRoomId($roomId)
            ->where('date', '>=', $period->getStartDate())
            ->where('date', '<', $period->getEndDate())
            ->get();

        return app(RoomQuotaFactory::class)->createCollectionFrom($models);
    }

    public function updateRoomQuota(int $roomId, CarbonPeriod $period, int $quota): void
    {
        EloquentQuota::whereRoomId($roomId)
            ->where('date', '>=', $period->getStartDate())
            ->where('date', '<', $period->getEndDate())
            ->update(['count_available' => $quota]);
    }

    public function closeRoomQuota(int $roomId, CarbonPeriod $period)
    {
        EloquentQuota::whereRoomId($roomId)
            ->where('date', '>=', $period->getStartDate())
            ->where('date', '<', $period->getEndDate())
            ->update(['type' => QuotaStatusEnum::Close]);
    }

    public function openRoomQuota(int $roomId, CarbonPeriod $period)
    {
        EloquentQuota::whereRoomId($roomId)
            ->where('date', '>=', $period->getStartDate())
            ->where('date', '<', $period->getEndDate())
            ->update(['type' => QuotaStatusEnum::Open]);
    }
}
