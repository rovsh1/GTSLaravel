<?php

declare(strict_types=1);

namespace Module\Pricing\Application\Dto;

use DateTimeInterface;

final class RoomDayCalculationResultDto
{
    public function __construct(
        public readonly DateTimeInterface $date,
        public readonly float $value,
        public readonly string $formula,
    ) {}

    public static function createZeroValue(DateTimeInterface $date): static
    {
        return new static(
            $date,
            0,
            '0'
        );
    }
}
