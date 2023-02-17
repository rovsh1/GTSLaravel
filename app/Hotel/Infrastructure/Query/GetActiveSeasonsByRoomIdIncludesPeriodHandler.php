<?php

namespace GTS\Hotel\Infrastructure\Query;

use Custom\Framework\Contracts\Bus\QueryHandlerInterface;
use Custom\Framework\Contracts\Bus\QueryInterface;

use GTS\Hotel\Application\Query\GetActiveSeasonsByRoomIdIncludesPeriod;
use GTS\Hotel\Domain\Entity\Season;
use GTS\Hotel\Domain\Factory\SeasonFactory;
use GTS\Hotel\Infrastructure\Models\Season as EloquentSeason;

class GetActiveSeasonsByRoomIdIncludesPeriodHandler implements QueryHandlerInterface
{
    /**
     * @param GetActiveSeasonsByRoomIdIncludesPeriod $query
     * @return Season[]
     */
    public function handle(QueryInterface $query): array
    {
        $seasons = EloquentSeason::query()
            ->whereRoomId($query->roomId)
            ->where('date_from', '<=', $query->period->getStartDate())
            ->where('date_to', '>=', $query->period->getEndDate())
            ->get();

        return app(SeasonFactory::class)->createCollectionFrom($seasons);
    }
}
