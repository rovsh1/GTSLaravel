<?php

namespace Module\Hotel\Infrastructure\Repository;

use Carbon\CarbonPeriod;
use Module\Hotel\Domain\Repository\QuotaEventRepositoryInterface;
use Module\Hotel\Domain\ValueObject\QuotaChangeTypeEnum;

class QuotaEventRepository implements QuotaEventRepositoryInterface
{
    public function registerChanges(
        int $roomId,
        CarbonPeriod $period,
        QuotaChangeTypeEnum $changeType,
        int $count,
        array $context
    ): void {
//@todo записать в базу
    }

    public function resetRoomQuota(int $roomId, CarbonPeriod $period): void
    {
        // TODO: Implement resetRoomQuota() method.
    }

}
