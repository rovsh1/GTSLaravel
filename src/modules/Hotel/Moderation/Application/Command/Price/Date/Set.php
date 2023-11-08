<?php

namespace Module\Hotel\Moderation\Application\Command\Price\Date;

use Carbon\CarbonInterface;
use Sdk\Module\Contracts\Bus\CommandInterface;

class Set implements CommandInterface
{
    public function __construct(
        public readonly CarbonInterface $date,
        public readonly int $seasonId,
        public readonly int $roomId,
        public readonly int $rateId,
        public readonly int $guestsCount,
        public readonly bool $isResident,
        public readonly float $price,
    ) {}
}
