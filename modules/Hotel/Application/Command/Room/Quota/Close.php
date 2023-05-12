<?php

namespace Module\Hotel\Application\Command\Room\Quota;

use Carbon\CarbonPeriod;
use Custom\Framework\Contracts\Bus\CommandInterface;

class Close implements CommandInterface
{
    public function __construct(
        public readonly int          $roomId,
        public readonly CarbonPeriod $period,
    ) {}
}
