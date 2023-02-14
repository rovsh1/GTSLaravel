<?php

namespace GTS\Hotel\Domain\Repository;

use Carbon\CarbonInterface;

interface QuotaRepositoryInterface
{
    public function reserveRoomQuotaByDate(int $roomId, CarbonInterface $date, int $quota);
}
