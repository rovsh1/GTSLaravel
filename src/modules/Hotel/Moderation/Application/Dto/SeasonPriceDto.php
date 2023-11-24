<?php

declare(strict_types=1);

namespace Module\Hotel\Moderation\Application\Dto;

final class SeasonPriceDto
{
    public function __construct(
        public readonly int $seasonId,
        public readonly int $roomId,
        public readonly int $rateId,
        public readonly int $guestsCount,
        public readonly bool $isResident,
        public readonly float $price,
        public readonly bool $hasDatePrices
    ) {}
}
