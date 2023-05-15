<?php

declare(strict_types=1);

namespace Module\Hotel\Application\Dto;

use Carbon\CarbonInterface;
use Custom\Framework\Foundation\Support\Dto\Dto;

class PriceDto extends Dto
{
    public function __construct(
        public readonly int $seasonId,
        public readonly int $roomId,
        public readonly int $rateId,
        public readonly int $guestsNumber,
        public readonly bool $isResident,
        public readonly float $price,
        public readonly int $currencyId,
        public readonly ?CarbonInterface $date = null,
    ) {}
}
