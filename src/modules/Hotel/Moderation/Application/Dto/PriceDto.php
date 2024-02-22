<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Application\Dto;

use Carbon\CarbonInterface;

final class PriceDto
{
    public function __construct(
        public readonly int $seasonId,
        public readonly int $roomId,
        public readonly int $rateId,
        public readonly int $guestsCount,
        public readonly bool $isResident,
        public readonly float $price,
        public readonly ?CarbonInterface $date = null,
    ) {}
}
