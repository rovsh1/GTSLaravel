<?php

namespace Module\Hotel\Infrastructure\Repository;

use Carbon\CarbonPeriod;
use Module\Hotel\Domain\Factory\SeasonFactory;
use Module\Hotel\Domain\Repository\SeasonRepositoryInterface;
use Module\Hotel\Infrastructure\Models\Season as EloquentSeason;

class SeasonRepository implements SeasonRepositoryInterface
{
    public function getActiveSeasonsByRoomIdIncludesPeriod(int $roomId, CarbonPeriod $period): array
    {
        $seasons = EloquentSeason::query()
            ->whereRoomId($roomId)
            ->where('date_from', '<=', $period->getStartDate())
            ->where('date_to', '>=', $period->getEndDate())
            ->get();

        return app(SeasonFactory::class)->createCollectionFrom($seasons);
    }
}
