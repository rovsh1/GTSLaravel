<?php

namespace Module\Hotel\Infrastructure\Query;

use Custom\Framework\Contracts\Bus\QueryHandlerInterface;
use Custom\Framework\Contracts\Bus\QueryInterface;
use Module\Hotel\Application\Query\GetActiveSeasonsByRoomIdIncludesPeriod;
use Module\Hotel\Domain\Entity\Season;
use Module\Hotel\Domain\Factory\SeasonFactory;
use Module\Hotel\Infrastructure\Models\Season as EloquentSeason;

class GetActiveSeasonsByRoomIdIncludesPeriodHandler implements QueryHandlerInterface
{
    /**
     * @param GetActiveSeasonsByRoomIdIncludesPeriod $query
     * @return Season[]
     */
    public function handle(QueryInterface|GetActiveSeasonsByRoomIdIncludesPeriod $query): array
    {
        $seasons = EloquentSeason::query()
            ->whereRoomId($query->roomId)
            ->where('date_from', '<=', $query->period->getStartDate())
            ->where('date_to', '>=', $query->period->getEndDate())
            ->get();

        return app(SeasonFactory::class)->createCollectionFrom($seasons);
    }
}
