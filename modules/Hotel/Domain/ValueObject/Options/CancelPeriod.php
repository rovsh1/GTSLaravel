<?php

declare(strict_types=1);

namespace Module\Hotel\Domain\ValueObject\Options;

use Carbon\CarbonPeriod;
use Module\Shared\Domain\ValueObject\ValueObjectInterface;

class CancelPeriod implements ValueObjectInterface
{
    public function __construct(
        private CarbonPeriod $period,
        private CancelMarkupPercent $noCheckInMarkup,
        private DailyMarkupCollection $dailyMarkups
    ) {}

    public function period(): CarbonPeriod
    {
        return $this->period;
    }

    public function noCheckInMarkup(): CancelMarkupPercent
    {
        return $this->noCheckInMarkup;
    }

    public function dailyMarkups(): DailyMarkupCollection
    {
        return $this->dailyMarkups;
    }
}
