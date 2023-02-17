<?php

namespace GTS\Hotel\Application\Query;

use Carbon\CarbonPeriod;

use Custom\Framework\Contracts\Bus\QueryInterface;

class GetActiveSeasonsByRoomIdIncludesPeriod implements QueryInterface
{
    public function __construct(
        public readonly int          $roomId,
        public readonly CarbonPeriod $period
    ) {}
}
