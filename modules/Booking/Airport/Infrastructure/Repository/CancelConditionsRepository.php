<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Infrastructure\Repository;

use Module\Booking\Airport\Domain\Repository\CancelConditionsRepositoryInterface;
use Module\Booking\Common\Domain\ValueObject\CancelCondition\CancelMarkupOption;
use Module\Booking\Common\Domain\ValueObject\CancelCondition\CancelPeriodTypeEnum;
use Module\Booking\Common\Domain\ValueObject\CancelCondition\DailyMarkupCollection;
use Module\Booking\Common\Domain\ValueObject\CancelCondition\DailyMarkupOption;
use Module\Booking\Common\Domain\ValueObject\CancelConditions;
use Module\Shared\Domain\ValueObject\Percent;

class CancelConditionsRepository implements CancelConditionsRepositoryInterface
{
    public function get(): CancelConditions
    {
        //@todo реализация хранения условий отмены
        return new CancelConditions(
            noCheckInMarkup: new CancelMarkupOption(
                percent: new Percent(100),
                cancelPeriodType: CancelPeriodTypeEnum::FULL_PERIOD,
            ),
            dailyMarkups: new DailyMarkupCollection([
                new DailyMarkupOption(
                    percent: new Percent(50),
                    cancelPeriodType: CancelPeriodTypeEnum::FULL_PERIOD,
                    daysCount: 2,
                )
            ]),
            cancelNoFeeDate: null,
        );
    }

    public function store(CancelConditions $cancelConditions): bool
    {
        return false;
    }
}
