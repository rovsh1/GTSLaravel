<?php

namespace Module\Hotel\Moderation\Application\Service\MarkupSettingsSetter;

use Module\Hotel\Moderation\Domain\Hotel\ValueObject\MarkupSettings\DailyMarkupOption;

class DailyMarkupOptionUpdater extends AbstractUpdater
{
    public function update(DailyMarkupOption $dailyMarkup, string $key, mixed $value): void
    {
        if (is_numeric($key) && is_array($value)) {
            $newDailyMarkup = (new DailyMarkupOptionBuilder())->build($value);
            $dailyMarkup->setPercent($newDailyMarkup->percent());
            $dailyMarkup->setCancelPeriodType($newDailyMarkup->cancelPeriodType());
            $dailyMarkup->setDaysCount($newDailyMarkup->daysCount());

            return;
        }
        $this->setByObjectKey($dailyMarkup, $key, $value);
    }
}
