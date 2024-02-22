<?php

declare(strict_types=1);

namespace Module\Hotel\Pricing\Application\Dto;

use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;

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
    ) {}

    public static function createZero(int $accommodationId, CarbonPeriod $period): static
    {
        $days = array_map(
            fn(CarbonInterface $date) => RoomDayCalculationResultDto::createZeroValue($date),
            $period->toArray()
        );

        return new static(
            $accommodationId,
            0,
            $days
        );
    }
}
