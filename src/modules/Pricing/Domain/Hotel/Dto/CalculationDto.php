<?php

declare(strict_types=1);

namespace Module\Pricing\Domain\Hotel\Dto;

final class CalculationDto
{
    public function __construct(
        public readonly float $value,
        public readonly string $formula
    ) {
    }
}
