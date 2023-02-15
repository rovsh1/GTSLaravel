<?php

namespace GTS\Hotel\Domain\Repository;

use Carbon\CarbonPeriod;
use GTS\Hotel\Domain\Entity\Season;

interface SeasonRepositoryInterface
{
    /**
     * @param int $hotelId
     * @param CarbonPeriod $period
     * @return Season[]
     */
    public function getActiveSeasonsIncludesPeriod(int $hotelId, CarbonPeriod $period): array;
}
