<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Application\Factory;

use Carbon\CarbonPeriod;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\CancelCondition\CancelMarkupOption;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\CancelCondition\CancelPeriodTypeEnum;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\CancelCondition\DailyMarkupCollection;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\CancelCondition\DailyMarkupOption;
use Module\Booking\HotelBooking\Domain\ValueObject\Details\CancelConditions;
use Module\Shared\Domain\ValueObject\Percent;

class CancelConditionsFactory
{
    public static function fromDto(array $cancelPeriods, CarbonPeriod $period): CancelConditions
    {
        $availablePeriod = collect($cancelPeriods)->first(
            fn(mixed $cancelPeriod) => $period->overlaps($cancelPeriod->from, $cancelPeriod->to)
        );
        if ($availablePeriod === null) {
            //@todo понять что тут делать
            throw new \Exception('Not found cancel period for booking');
        }

        $maxDaysCount = null;
        $dailyMarkups = new DailyMarkupCollection();
        foreach ($availablePeriod->dailyMarkups as $dailyMarkup) {
            if ($dailyMarkup->daysCount > $maxDaysCount) {
                $maxDaysCount = $dailyMarkup->daysCount;
            }
            $dailyMarkups->add(
                new DailyMarkupOption(
                    new Percent($dailyMarkup->percent),
                    CancelPeriodTypeEnum::from($dailyMarkup->cancelPeriodType),
                    $dailyMarkup->daysCount
                )
            );
        }
        $cancelNoFeeDate = null;
        if ($maxDaysCount !== null) {
            $cancelNoFeeDate = $period->getStartDate()->clone()->subDays($maxDaysCount)->toImmutable();
        }

        return new CancelConditions(
            new CancelMarkupOption(
                new Percent($availablePeriod->noCheckInMarkup->percent),
                CancelPeriodTypeEnum::from($availablePeriod->noCheckInMarkup->cancelPeriodType)
            ),
            $dailyMarkups,
            $cancelNoFeeDate
        );
    }
}
