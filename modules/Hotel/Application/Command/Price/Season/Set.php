<?php

namespace Module\Hotel\Application\Command\Price\Season;

use Sdk\Module\Contracts\Bus\CommandInterface;

class Set implements CommandInterface
{
    public function __construct(
        public readonly int $seasonId,
        public readonly int $roomId,
        public readonly int $rateId,
        public readonly int $guestsCount,
        public readonly bool $isResident,
        public readonly ?float $price,
    ) {}
}
