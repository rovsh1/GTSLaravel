<?php

declare(strict_types=1);

namespace Module\Hotel\Pricing\Domain\Hotel\Dto;

final class CalculationDto
{
    public function __construct(
        public readonly float $value,
        public readonly string $formula
    ) {
    }
}
