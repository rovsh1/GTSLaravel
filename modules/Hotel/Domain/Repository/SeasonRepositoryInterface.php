<?php

namespace Module\Hotel\Domain\Repository;

use Carbon\CarbonPeriod;
use Module\Hotel\Domain\Entity\Season;

interface SeasonRepositoryInterface
{
    /**
     * @param int $roomId
     * @param CarbonPeriod $period
     * @return Season[]
     */
    public function getActiveSeasonsByRoomIdIncludesPeriod(int $roomId, CarbonPeriod $period): array;
}
