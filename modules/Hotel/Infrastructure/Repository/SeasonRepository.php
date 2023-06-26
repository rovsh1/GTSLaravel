<?php

declare(strict_types=1);

namespace Module\Hotel\Infrastructure\Repository;

use Carbon\CarbonPeriod;
use Module\Hotel\Domain\Factory\SeasonFactory;
use Module\Hotel\Infrastructure\Models\Season as EloquentSeason;
use Module\Hotel\Domain\Repository\SeasonRepositoryInterface;

class SeasonRepository implements SeasonRepositoryInterface
{
    public function getActiveSeasonsByRoomIdIncludesPeriod(int $roomId, CarbonPeriod $period): array
    {
        $seasons = EloquentSeason::query()
            ->whereRoomId($roomId)
            ->where('date_start', '<=', $period->getStartDate())
            ->where('date_end', '>=', $period->getEndDate())
            ->get();

        return app(SeasonFactory::class)->createCollectionFrom($seasons);
    }
}
