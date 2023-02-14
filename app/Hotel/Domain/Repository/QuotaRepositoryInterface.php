<?php

namespace GTS\Hotel\Domain\Repository;

use Carbon\CarbonPeriod;

interface QuotaRepositoryInterface
{
    public function reserveRoomQuotaByDate(int $roomId, CarbonPeriod $period, int $quota);
}
