<?php

namespace GTS\Hotel\Domain\Entity;

use Carbon\CarbonPeriod;

class Contract
{
    public function __construct(
        public readonly int          $id,
        public readonly int          $number,
        public readonly CarbonPeriod $period,
    ) {}
}
