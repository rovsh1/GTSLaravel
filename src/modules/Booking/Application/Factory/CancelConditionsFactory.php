<?php

declare(strict_types=1);

namespace Module\Booking\Application\Factory;

use Carbon\CarbonInterface;
use Illuminate\Support\Arr;
use Module\Booking\Domain\Booking\Adapter\SupplierAdapterInterface;
use Module\Booking\Domain\Shared\ValueObject\CancelCondition\CancelMarkupOption;
use Module\Booking\Domain\Shared\ValueObject\CancelCondition\CancelPeriodTypeEnum;
use Module\Booking\Domain\Shared\ValueObject\CancelCondition\DailyMarkupCollection;
use Module\Booking\Domain\Shared\ValueObject\CancelCondition\DailyMarkupOption;
use Module\Booking\Domain\Shared\ValueObject\CancelConditions;
use Module\Shared\Enum\ServiceTypeEnum;
use Module\Shared\ValueObject\Percent;
use Module\Supplier\Application\Response\CancelConditionsDto;
use Module\Supplier\Application\Response\DailyMarkupDto;

class CancelConditionsFactory
{
    public function __construct(
        private readonly SupplierAdapterInterface $supplierAdapter
    ) {}

    public function build(ServiceTypeEnum $serviceType, CarbonInterface|null $bookingDate = null): CancelConditions
    {
        $baseCancelConditions = $this->getSupplierCancelConditions($serviceType);
        $cancelNoFeeDate = null;
        $dailyMarkupOptions = [];
        $maxDaysCount = Arr::first($baseCancelConditions->dailyMarkups)?->daysCount;
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

    private function getSupplierCancelConditions(ServiceTypeEnum $serviceType): CancelConditionsDto
    {
        return match ($serviceType) {
            ServiceTypeEnum::CIP_ROOM_IN_AIRPORT => $this->supplierAdapter->getAirportCancelConditions(),
            ServiceTypeEnum::CAR_RENT_WITH_DRIVER,
            ServiceTypeEnum::TRANSFER_TO_RAILWAY,
            ServiceTypeEnum::TRANSFER_FROM_RAILWAY,
            ServiceTypeEnum::TRANSFER_TO_AIRPORT,
            ServiceTypeEnum::INTERCITY_TRANSFER,
            ServiceTypeEnum::DAY_CAR_TRIP,
            ServiceTypeEnum::TRANSFER_FROM_AIRPORT => $this->supplierAdapter->getTransferCancelConditions(),
            default => throw new \Exception('Service type cancel conditions not implement')
        };
    }
}
