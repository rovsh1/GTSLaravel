<?php

namespace Module\Hotel\Application\Query\Price\Date;

use Carbon\CarbonInterface;
use Module\Shared\Enum\CurrencyEnum;
use Sdk\Module\Contracts\Bus\QueryInterface;

class Find implements QueryInterface
{
    public function __construct(
        public readonly int $roomId,
        public readonly int $seasonId,
        public readonly int $rateId,
        public readonly bool $isResident,
        public readonly int $guestsCount,
        public readonly CarbonInterface $date
    ) {}
}
