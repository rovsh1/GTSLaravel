<?php

namespace Module\Hotel\Application\Command\Room\Quota;

use Carbon\CarbonPeriod;
use Custom\Framework\Contracts\Bus\CommandInterface;

class Open implements CommandInterface
{
    public function __construct(
        public readonly int          $roomId,
        public readonly CarbonPeriod $period,
    ) {}
}
