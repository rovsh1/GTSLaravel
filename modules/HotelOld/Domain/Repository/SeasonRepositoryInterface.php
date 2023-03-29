<?php

namespace Module\HotelOld\Domain\Repository;

use Carbon\CarbonPeriod;
use Module\HotelOld\Domain\Entity\Season;

interface SeasonRepositoryInterface
{
    /**
     * @param int $roomId
     * @param CarbonPeriod $period
     * @return Season[]
     */
    public function getActiveSeasonsByRoomIdIncludesPeriod(int $roomId, CarbonPeriod $period): array;
}
