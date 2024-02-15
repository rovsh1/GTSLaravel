<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Domain\Booking\Factory;

use Carbon\CarbonImmutable;
use Illuminate\Support\Arr;
use Module\Booking\Moderation\Domain\Booking\Exception\NotFoundServiceCancelConditions;
use Module\Booking\Shared\Domain\Booking\Adapter\SupplierAdapterInterface;
use Module\Supplier\Moderation\Application\Response\CancelConditionsDto;
use Module\Supplier\Moderation\Application\Response\DailyMarkupDto;
use Sdk\Booking\ValueObject\CancelCondition\CancelFeePeriodTypeEnum;
use Sdk\Booking\ValueObject\CancelCondition\CancelFeeValue;
use Sdk\Booking\ValueObject\CancelCondition\DailyCancelFeeValue;
use Sdk\Booking\ValueObject\CancelCondition\DailyCancelFeeValueCollection;
use Sdk\Booking\ValueObject\CancelCondition\FeeValue;
use Sdk\Booking\ValueObject\CancelConditions;
use Sdk\Booking\ValueObject\CarBidCollection;
use Sdk\Module\Support\DateTimeImmutable;

class TransferCancelConditionsFactory
{
    public function __construct(
        private readonly SupplierAdapterInterface $supplierAdapter
    ) {}

    /**
     * @param int $serviceId
     * @param CarBidCollection $carBids
     * @param \DateTimeInterface $bookingDate
     * @return CancelConditions|null
     * @throws NotFoundServiceCancelConditions
     */
    public function build(
        int $serviceId,
        CarBidCollection $carBids,
        DateTimeImmutable $bookingDate
    ): ?CancelConditions {
        $cancelConditions = null;
        foreach ($carBids as $carBid) {
            $carCancelConditions = $this->supplierAdapter->getCarCancelConditions(
                $serviceId,
                $carBid->carId()->value(),
                $bookingDate
            );
            if ($carCancelConditions === null) {
                throw new NotFoundServiceCancelConditions();
            }
            $carCancelConditions = $this->buildCarCancelConditions(
                $carCancelConditions,
                $carBid->clientPriceValue(),
                $bookingDate
            );
            if ($cancelConditions === null) {
                $cancelConditions = $carCancelConditions;
                continue;
            }
            $cancelConditions = $this->mergeCancelConditions($cancelConditions, $carCancelConditions, $bookingDate);
        }

        return $cancelConditions;
    }

    private function buildCarCancelConditions(
        CancelConditionsDto $carCancelConditions,
        float $price,
        DateTimeImmutable $bookingDate
    ): CancelConditions {
        $cancelNoFeeDate = null;
        $dailyMarkupOptions = [];
        $maxDaysCount = Arr::first($carCancelConditions->dailyMarkups)?->daysCount;

        if ($maxDaysCount !== null && $bookingDate !== null) {
            $cancelNoFeeDate = $bookingDate->modify("-{$maxDaysCount} days");
            $cancelNoFeeDate = CarbonImmutable::createFromInterface($cancelNoFeeDate);
            $dailyMarkupOptions = collect($carCancelConditions->dailyMarkups)->map(
                fn(DailyMarkupDto $dailyMarkupDto) => new DailyCancelFeeValue(
                    value: FeeValue::createAbsolute(
                        $this->calculatePercentValue($dailyMarkupDto->percent, $price)
                    ),
                    cancelPeriodType: CancelFeePeriodTypeEnum::FULL_PERIOD,
                    daysCount: $dailyMarkupDto->daysCount
                )
            );
        }

        return new CancelConditions(
            noCheckInMarkup: new CancelFeeValue(
                value: FeeValue::createAbsolute(
                    $this->calculatePercentValue($carCancelConditions->noCheckInMarkup->percent, $price)
                ),
                cancelPeriodType: CancelFeePeriodTypeEnum::FULL_PERIOD
            ),
            dailyMarkups: new DailyCancelFeeValueCollection($dailyMarkupOptions),
            cancelNoFeeDate: $cancelNoFeeDate
        );
    }

    private function mergeCancelConditions(
        CancelConditions $baseCancelConditions,
        CancelConditions $carCancelConditions,
        DateTimeImmutable $bookingDate
    ): CancelConditions {
        $noCheckInMarkupValue = $baseCancelConditions->noCheckInMarkup()->value()->value(
            ) + $carCancelConditions->noCheckInMarkup()->value()->value();
        $noCheckInMarkup = new CancelFeeValue(
            value: FeeValue::createAbsolute($noCheckInMarkupValue),
            cancelPeriodType: CancelFeePeriodTypeEnum::FULL_PERIOD,
        );

        /** @var DailyCancelFeeValue[] $dailyCancelFeeValues */
        $dailyCancelFeeValues = [
            ...$baseCancelConditions->dailyMarkups()->all(),
            ...$carCancelConditions->dailyMarkups()->all(),
        ];

        $sumByDays = [];
        foreach ($dailyCancelFeeValues as $dailyCancelFeeValue) {
            $daysCountKey = $dailyCancelFeeValue->daysCount();
            if (!array_key_exists($daysCountKey, $sumByDays)) {
                $sumByDays[$daysCountKey] = 0;
            }
            $sumByDays[$daysCountKey] += $dailyCancelFeeValue->value()->value();
        }

        //сортируем массив по убыванию дней, чтобы просуммировать сумму всех предыдущих дней
        krsort($sumByDays);
        foreach ($sumByDays as $daysCount => $sum) {
            $daysCountKey = $daysCount - 1;
            if (!array_key_exists($daysCountKey, $sumByDays)) {
                break;
            }
            $sumByDays[$daysCountKey] += $sum;
        }

        $dailyCancelFeeValues = [];
        foreach ($sumByDays as $daysCount => $value) {
            $dailyCancelFeeValues[] = new DailyCancelFeeValue(
                value: FeeValue::createAbsolute($value),
                cancelPeriodType: CancelFeePeriodTypeEnum::FULL_PERIOD,
                daysCount: $daysCount,
            );
        }

        $cancelNoFeeDate = null;
        $maxDaysCount = max(
            Arr::first($carCancelConditions->dailyMarkups()->all())?->daysCount(),
            Arr::first($baseCancelConditions->dailyMarkups()->all())?->daysCount(),
        );
        if ($maxDaysCount !== null) {
            $cancelNoFeeDate = $bookingDate->modify("-{$maxDaysCount} days");
        }

        return new CancelConditions(
            noCheckInMarkup: $noCheckInMarkup,
            dailyMarkups: new DailyCancelFeeValueCollection($dailyCancelFeeValues),
            cancelNoFeeDate: $cancelNoFeeDate,
        );
    }

    private function calculatePercentValue(int $percent, float $value): int
    {
        return (int)($value * $percent / 100);
    }
}
