<?php

namespace GTS\Hotel\Infrastructure\Repository;

use Carbon\CarbonPeriod;

use GTS\Hotel\Domain\Factory\SeasonFactory;
use GTS\Hotel\Domain\Repository\SeasonRepositoryInterface;
use GTS\Hotel\Infrastructure\Models\Season as EloquentSeason;

class SeasonRepository implements SeasonRepositoryInterface
{
    /**
     * @param int $hotelId
     * @param CarbonPeriod $period
     * @return \GTS\Hotel\Domain\Entity\Season[]
     */
    public function getActiveSeasonsIncludesPeriod(int $hotelId, CarbonPeriod $period): array
    {
        $seasons = EloquentSeason::whereHotelId($hotelId)
            ->where('date_from', '<=', $period->getStartDate())
            ->where('date_to', '>=', $period->getEndDate())
            ->get();

        return app(SeasonFactory::class)->createCollectionFrom($seasons);
    }
}
