<?php

declare(strict_types=1);

namespace Module\Booking\Application\Admin\ServiceBooking\Factory;

use Carbon\CarbonInterface;
use Module\Booking\Domain\ServiceBooking\Adapter\SupplierAdapterInterface;
use Module\Booking\Domain\Shared\ValueObject\CancelCondition\CancelMarkupOption;
use Module\Booking\Domain\Shared\ValueObject\CancelCondition\CancelPeriodTypeEnum;
use Module\Booking\Domain\Shared\ValueObject\CancelCondition\DailyMarkupCollection;
use Module\Booking\Domain\Shared\ValueObject\CancelCondition\DailyMarkupOption;
use Module\Booking\Domain\Shared\ValueObject\CancelConditions;
use Module\Shared\ValueObject\Percent;
use Module\Supplier\Application\Response\DailyMarkupDto;

class CancelConditionsFactory
{
    public function __construct(
        private readonly SupplierAdapterInterface $supplierAdapter
    ) {}

    public function build(CarbonInterface|null $bookingDate = null): CancelConditions
    {
        $baseCancelConditions = $this->supplierAdapter->getTransferCancelConditions();
        $cancelNoFeeDate = null;
        $dailyMarkupOptions = [];
        $maxDaysCount = \Arr::first($baseCancelConditions->dailyMarkups)?->daysCount;
        if ($maxDaysCount !== null && $bookingDate !== null) {
            $cancelNoFeeDate = $bookingDate->clone()->subDays($maxDaysCount)->toImmutable();
            $dailyMarkupOptions = collect($baseCancelConditions->dailyMarkups)->map(
                fn(DailyMarkupDto $dailyMarkupDto) => new DailyMarkupOption(
                    percent: new Percent($dailyMarkupDto->percent),
                    cancelPeriodType: CancelPeriodTypeEnum::FULL_PERIOD,
                    daysCount: $dailyMarkupDto->daysCount
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
