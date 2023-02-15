<?php

namespace GTS\Hotel\Application\Command;

use Carbon\CarbonPeriod;

use Custom\Framework\Contracts\Bus\CommandInterface;

class CloseRoomQuota implements CommandInterface
{
    public function __construct(
        public readonly int          $roomId,
        public readonly CarbonPeriod $period,
        public readonly int          $priceRateId,
    ) {}
}
