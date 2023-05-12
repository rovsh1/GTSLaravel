<?php

namespace Module\Hotel\Application\Query\Quota;

use Carbon\CarbonPeriod;
use Custom\Framework\Contracts\Bus\QueryInterface;

class GetStopped implements QueryInterface
{
    public function __construct(
        public readonly int $hotelId,
        public readonly CarbonPeriod $period,
        public readonly ?int $roomId = null,
    ) {}
}
