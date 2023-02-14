<?php

namespace GTS\Hotel\Application\Command;

use Carbon\CarbonInterface;
use Custom\Framework\Contracts\Bus\CommandInterface;

class ReserveQuota implements CommandInterface
{
    public function __construct(
        public readonly int             $roomId,
        public readonly CarbonInterface $date,
        public readonly int             $count = 1,
    ) {}
}
