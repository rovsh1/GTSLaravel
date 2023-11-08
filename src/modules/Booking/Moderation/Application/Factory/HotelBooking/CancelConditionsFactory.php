<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Factory\HotelBooking;

use Carbon\CarbonPeriod;
use Module\Booking\Shared\Domain\Shared\ValueObject\CancelCondition\CancelMarkupOption;
use Module\Booking\Shared\Domain\Shared\ValueObject\CancelCondition\CancelPeriodTypeEnum;
use Module\Booking\Shared\Domain\Shared\ValueObject\CancelCondition\DailyMarkupCollection;
use Module\Booking\Shared\Domain\Shared\ValueObject\CancelCondition\DailyMarkupOption;
use Module\Booking\Shared\Domain\Shared\ValueObject\CancelConditions;
use Module\Hotel\Moderation\Application\Admin\ResponseDto\MarkupSettings\CancelPeriodDto;
use Module\Shared\ValueObject\Percent;

class CancelConditionsFactory
{
    /**
     * @param CancelPeriodDto[] $cancelPeriods
     * @param CarbonPeriod $period
     * @return CancelConditions
     * @throws \Exception
     */
    public static function fromDto(array $cancelPeriods, CarbonPeriod $period): CancelConditions
    {
        /** @var CancelPeriodDto $availablePeriod */
        $availablePeriod = collect($cancelPeriods)->first(
            fn(mixed $cancelPeriod) => $period->overlaps($cancelPeriod->from, $cancelPeriod->to)
        );

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
