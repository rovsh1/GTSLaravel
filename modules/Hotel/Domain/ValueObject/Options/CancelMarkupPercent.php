<?php

declare(strict_types=1);

namespace Module\Hotel\Domain\ValueObject\Options;

use Module\Shared\Domain\ValueObject\Percent;

class CancelMarkupPercent extends Percent
{
    public function __construct(
        int $value,
        private PeriodTypeEnum $periodType
    ) {
        parent::__construct($value);
    }

    public function periodType(): PeriodTypeEnum
    {
        return $this->periodType;
    }
}
