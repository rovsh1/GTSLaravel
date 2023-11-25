<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Factory\HotelBooking;

use Carbon\CarbonPeriod;
use Module\Booking\Shared\Domain\Shared\ValueObject\CancelCondition\CancelFeePeriodTypeEnum;
use Module\Booking\Shared\Domain\Shared\ValueObject\CancelCondition\CancelFeeValue;
use Module\Booking\Shared\Domain\Shared\ValueObject\CancelCondition\DailyCancelFeeValue;
use Module\Booking\Shared\Domain\Shared\ValueObject\CancelCondition\DailyCancelFeeValueCollection;
use Module\Booking\Shared\Domain\Shared\ValueObject\CancelCondition\FeeValue;
use Module\Booking\Shared\Domain\Shared\ValueObject\CancelConditions;
use Module\Hotel\Moderation\Application\Dto\MarkupSettings\CancelPeriodDto;

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
        $dailyMarkups = new DailyCancelFeeValueCollection();
        foreach ($availablePeriod->dailyMarkups as $dailyMarkup) {
            if ($dailyMarkup->daysCount > $maxDaysCount) {
                $maxDaysCount = $dailyMarkup->daysCount;
            }
            $dailyMarkups->add(
                new DailyCancelFeeValue(
                    FeeValue::createPercent($dailyMarkup->percent),
                    CancelFeePeriodTypeEnum::from($dailyMarkup->cancelPeriodType),
                    $dailyMarkup->daysCount
                )
            );
        }
        $cancelNoFeeDate = null;
        if ($maxDaysCount !== null) {
            $cancelNoFeeDate = $period->getStartDate()->clone()->subDays($maxDaysCount)->toImmutable();
        }

        return new CancelConditions(
            new CancelFeeValue(
                FeeValue::createPercent($availablePeriod->noCheckInMarkup->percent),
                CancelFeePeriodTypeEnum::from($availablePeriod->noCheckInMarkup->cancelPeriodType)
            ),
            $dailyMarkups,
            $cancelNoFeeDate
        );
    }
}
