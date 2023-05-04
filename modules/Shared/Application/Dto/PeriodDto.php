<?php

declare(strict_types=1);

namespace Module\Shared\Application\Dto;

use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use Custom\Framework\Foundation\Support\Dto\Dto;

class PeriodDto extends Dto
{
    public function __construct(
        public readonly CarbonInterface $from,
        public readonly CarbonInterface $to,
    ) {}

    public static function fromCarbonPeriod(CarbonPeriod $period): static
    {
        return new static(
            $period->getStartDate(),
            $period->getEndDate()
        );
    }
}
