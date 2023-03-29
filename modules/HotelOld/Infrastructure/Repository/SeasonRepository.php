<?php

namespace Module\HotelOld\Infrastructure\Repository;

use Carbon\CarbonPeriod;
use Module\HotelOld\Domain\Factory\SeasonFactory;
use Module\HotelOld\Domain\Repository\SeasonRepositoryInterface;
use Module\HotelOld\Infrastructure\Models\Season as EloquentSeason;

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
