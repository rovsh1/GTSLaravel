<?php

namespace Module\Hotel\Infrastructure\Repository;

use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Module\Hotel\Domain\Repository\QuotaEventRepositoryInterface;
use Module\Hotel\Domain\ValueObject\QuotaChangeTypeEnum;
use Module\Hotel\Infrastructure\Models\Room\Quota;

class QuotaEventRepository implements QuotaEventRepositoryInterface
{
    public function registerChanges(
        int $roomId,
        CarbonPeriod $period,
        QuotaChangeTypeEnum $changeType,
        int $count,
        array $context
    ): void {
        DB::transaction(function () use ($roomId, $period, $changeType, $count, $context) {
            $quotas = Quota::whereRoomId($roomId)->wherePeriod($period)->get();
        });
    }

    public function resetRoomQuota(int $roomId, CarbonPeriod $period): void
    {
        // TODO: Implement resetRoomQuota() method.
    }

}
