<?php

namespace Module\Hotel\Domain\Repository;

use Carbon\CarbonPeriod;
use Module\Hotel\Domain\ValueObject\QuotaChangeTypeEnum;

interface QuotaEventRepositoryInterface
{
    public function registerChanges(int $roomId, CarbonPeriod $period, QuotaChangeTypeEnum $changeType, int $count, array $context): void;

    public function resetRoomQuota(int $roomId, CarbonPeriod $period): void;
}
