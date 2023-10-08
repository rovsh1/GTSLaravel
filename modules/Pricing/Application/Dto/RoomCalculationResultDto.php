<?php

declare(strict_types=1);

namespace Module\Pricing\Application\Dto;

final class RoomCalculationResultDto
{
    /**
     * @param float $price
     * @param int $roomId
     * @param RoomDayCalculationResultDto[] $dates
     */
    public function __construct(
        public readonly int $roomId,
        public readonly float $price,
        public readonly array $dates,
    ) {
    }
}
