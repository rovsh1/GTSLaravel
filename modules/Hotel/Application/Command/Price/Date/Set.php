<?php

namespace Module\Hotel\Application\Command\Price\Date;

use Carbon\CarbonInterface;
use Custom\Framework\Contracts\Bus\CommandInterface;

class Set implements CommandInterface
{
    public function __construct(
        public readonly CarbonInterface $date,
        public readonly int $seasonId,
        public readonly int $roomId,
        public readonly int $rateId,
        public readonly int $guestsNumber,
        public readonly bool $isResident,
        public readonly float $price,
        public readonly int $currencyId
    ) {}
}
