<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Application\Factory;

use Carbon\CarbonInterface;
use Module\Booking\Airport\Domain\Adapter\SupplierAdapterInterface;
use Module\Booking\Common\Domain\ValueObject\CancelCondition\CancelMarkupOption;
use Module\Booking\Common\Domain\ValueObject\CancelCondition\CancelPeriodTypeEnum;
use Module\Booking\Common\Domain\ValueObject\CancelCondition\DailyMarkupCollection;
use Module\Booking\Common\Domain\ValueObject\CancelCondition\DailyMarkupOption;
use Module\Booking\Common\Domain\ValueObject\CancelConditions;
use Module\Shared\Domain\ValueObject\Percent;
use Module\Supplier\Application\Response\DailyMarkupDto;
use Module\Supplier\Domain\Supplier\Repository\CancelConditionsRepositoryInterface;

class CancelConditionsFactory
{
    public function __construct(
        private readonly SupplierAdapterInterface $supplierAdapter
    ) {}

    public function build(CarbonInterface $bookingDate): CancelConditions
    {
        $baseCancelConditions = $this->supplierAdapter->getAirportCancelConditions();
        $cancelNoFeeDate = null;
        $dailyMarkupOptions = [];
        $maxDaysCount = \Arr::first($baseCancelConditions->dailyMarkups)?->daysCount;
        if ($maxDaysCount !== null) {
            $cancelNoFeeDate = $bookingDate->clone()->subDays($maxDaysCount)->toImmutable();
            $dailyMarkupOptions = collect($baseCancelConditions->dailyMarkups)->map(
                fn(DailyMarkupDto $dailyMarkupDto) => new DailyMarkupOption(
                    percent: new Percent($dailyMarkupDto->percent),
                    daysCount: $dailyMarkupDto->daysCount,
                    cancelPeriodType: CancelPeriodTypeEnum::FULL_PERIOD
                )
            );
        }

        return new CancelConditions(
            noCheckInMarkup: new CancelMarkupOption(
                percent: new Percent($baseCancelConditions->noCheckInMarkup->percent),
                cancelPeriodType: CancelPeriodTypeEnum::FULL_PERIOD
            ),
            dailyMarkups: new DailyMarkupCollection($dailyMarkupOptions),
            cancelNoFeeDate: $cancelNoFeeDate
        );
    }
}
