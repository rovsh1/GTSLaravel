<?php

declare(strict_types=1);

namespace Module\Pricing\Application\Dto;

final class RoomCalculationResultDto
{
    /**
     * @param int $accommodationId
     * @param float $price
     * @param RoomDayCalculationResultDto[] $dates
     */
    public function __construct(
        public readonly int $accommodationId,
        public readonly float $price,
        public readonly array $dates,
    ) {
    }
}
