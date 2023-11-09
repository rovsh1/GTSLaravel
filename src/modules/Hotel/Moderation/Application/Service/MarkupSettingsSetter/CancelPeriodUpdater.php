<?php

namespace Module\Hotel\Moderation\Application\Service\MarkupSettingsSetter;

use Carbon\CarbonImmutable;
use Carbon\CarbonPeriodImmutable;
use Module\Hotel\Moderation\Domain\Hotel\ValueObject\MarkupSettings\CancelPeriod;

class CancelPeriodUpdater extends AbstractUpdater
{
    public function update(CancelPeriod $cancelPeriod, string $key, mixed $value): void
    {
        if (is_numeric($key) && is_array($value)) {
            $newPeriod = (new CancelPeriodBuilder())->build($value);
            $cancelPeriod->setPeriod($newPeriod->period());
            $cancelPeriod->setNoCheckInMarkup($newPeriod->noCheckInMarkup());

            return;
        }

        $startDate = $cancelPeriod->period()->getStartDate();
        $endDate = $cancelPeriod->period()->getEndDate();
        if ($key === 'from') {
            $startDate = new CarbonImmutable($value);
            $period = new CarbonPeriodImmutable($startDate, $endDate, $cancelPeriod->period()->getDateInterval());
            $cancelPeriod->setPeriod($period);
        } elseif ($key === 'to') {
            $endDate = new CarbonImmutable($value);
            $period = new CarbonPeriodImmutable($startDate, $endDate, $cancelPeriod->period()->getDateInterval());
            $cancelPeriod->setPeriod($period);
        } else {
            $this->setByObjectKey($cancelPeriod, $key, $value);
        }
    }
}