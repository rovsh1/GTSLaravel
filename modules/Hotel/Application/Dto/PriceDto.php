<?php

declare(strict_types=1);

namespace Module\Hotel\Application\Dto;

use Carbon\CarbonInterface;

class PriceDto extends \Sdk\Module\Foundation\Support\Dto\Dto
{
    public function __construct(
        public readonly int $seasonId,
        public readonly int $roomId,
        public readonly int $rateId,
        public readonly int $guestsCount,
        public readonly bool $isResident,
        public readonly float $price,
        public readonly int $currencyId,
        public readonly ?CarbonInterface $date = null,
    ) {}
}
