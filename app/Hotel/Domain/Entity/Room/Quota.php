<?php

namespace GTS\Hotel\Domain\Entity\Room;

use Carbon\CarbonInterface;

use GTS\Hotel\Domain\Entity\Room;

class Quota
{
    public function __construct(
        public readonly ?Room           $room,
        public readonly CarbonInterface $date,
        public readonly int             $countAvailable,
        public readonly int             $countBooked,
        public readonly int             $preparePeriod,
    ) {}
}
