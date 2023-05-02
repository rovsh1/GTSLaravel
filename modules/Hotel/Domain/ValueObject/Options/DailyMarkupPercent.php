<?php

declare(strict_types=1);

namespace Module\Hotel\Domain\ValueObject\Options;

class DailyMarkupPercent extends CancelMarkupPercent
{
    public function __construct(
        int $value,
        PeriodTypeEnum $periodType,
        private int $daysCount
    ) {
        parent::__construct($value, $periodType);
    }

    public function daysCount(): int
    {
        return $this->daysCount;
    }
}
