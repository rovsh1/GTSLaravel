<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Application\Factory;

use Illuminate\Support\Arr;
use Module\Booking\Shared\Domain\Booking\Adapter\SupplierAdapterInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\ServiceId;
use Module\Booking\Shared\Domain\Shared\ValueObject\CancelCondition\CancelMarkupOption;
use Module\Booking\Shared\Domain\Shared\ValueObject\CancelCondition\CancelPeriodTypeEnum;
use Module\Booking\Shared\Domain\Shared\ValueObject\CancelCondition\DailyMarkupCollection;
use Module\Booking\Shared\Domain\Shared\ValueObject\CancelCondition\DailyMarkupOption;
use Module\Booking\Shared\Domain\Shared\ValueObject\CancelConditions;
use Module\Shared\Enum\ServiceTypeEnum;
use Module\Shared\ValueObject\Percent;
use Module\Supplier\Moderation\Application\Response\CancelConditionsDto;
use Module\Supplier\Moderation\Application\Response\DailyMarkupDto;

class CancelConditionsFactory
{
    public function __construct(
        private readonly SupplierAdapterInterface $supplierAdapter
    ) {}

    public function build(
        ServiceId $serviceId,
        ServiceTypeEnum $serviceType,
        \DateTimeInterface|null $bookingDate
    ): ?CancelConditions {
        if ($bookingDate === null) {
            return null;
        }
        $baseCancelConditions = $this->getSupplierCancelConditions($serviceId, $serviceType, $bookingDate);
        if ($baseCancelConditions === null) {
            return null;
        }

        return $this->fromDto($baseCancelConditions, $bookingDate);
    }

    public function fromDto(CancelConditionsDto $cancelConditionsDto, \DateTimeInterface $bookingDate): CancelConditions
    {
        $cancelNoFeeDate = null;
        $dailyMarkupOptions = [];
        $maxDaysCount = Arr::first($cancelConditionsDto->dailyMarkups)?->daysCount;
        if ($maxDaysCount !== null && $bookingDate !== null) {
            $cancelNoFeeDate = $bookingDate->clone()->subDays($maxDaysCount)->toImmutable();
            $dailyMarkupOptions = collect($cancelConditionsDto->dailyMarkups)->map(
                fn(DailyMarkupDto $dailyMarkupDto) => new DailyMarkupOption(
                    percent: new Percent($dailyMarkupDto->percent),
                    cancelPeriodType: CancelPeriodTypeEnum::FULL_PERIOD,
                    daysCount: $dailyMarkupDto->daysCount
                )
            );
        }

        return new CancelConditions(
            noCheckInMarkup: new CancelMarkupOption(
                percent: new Percent($cancelConditionsDto->noCheckInMarkup->percent),
                cancelPeriodType: CancelPeriodTypeEnum::FULL_PERIOD
            ),
            dailyMarkups: new DailyMarkupCollection($dailyMarkupOptions),
            cancelNoFeeDate: $cancelNoFeeDate
        );
    }

    private function getSupplierCancelConditions(
        ServiceId $serviceId,
        ServiceTypeEnum $serviceType,
        \DateTimeInterface $bookingDate,
    ): ?CancelConditionsDto {
        return match ($serviceType) {
            ServiceTypeEnum::CIP_MEETING_IN_AIRPORT,
            ServiceTypeEnum::CIP_SENDOFF_IN_AIRPORT => $this->supplierAdapter->getAirportCancelConditions($serviceId->value(), $bookingDate),
            ServiceTypeEnum::CAR_RENT_WITH_DRIVER,
            ServiceTypeEnum::TRANSFER_TO_RAILWAY,
            ServiceTypeEnum::TRANSFER_FROM_RAILWAY,
            ServiceTypeEnum::TRANSFER_TO_AIRPORT,
            ServiceTypeEnum::INTERCITY_TRANSFER,
            ServiceTypeEnum::DAY_CAR_TRIP,
            ServiceTypeEnum::TRANSFER_FROM_AIRPORT => null,
            ServiceTypeEnum::OTHER_SERVICE => $this->supplierAdapter->getOtherCancelConditions(
                $serviceId->value(),
                $bookingDate
            ),
            default => throw new \Exception('Service type cancel conditions not implement')
        };
    }
}
