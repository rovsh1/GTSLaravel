<?php

declare(strict_types=1);

namespace Module\Booking\Moderation\Domain\Booking\Factory;

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

class TransferCancelConditionsFactory
{
    public function __construct(
        private readonly SupplierAdapterInterface $supplierAdapter
    ) {}

    public function build(
        ?CancelConditions $cancelConditions,
        int $serviceId,
        int $carId,
        float $pricePerCar,
        int $countCars,
        \DateTimeInterface $bookingDate
    ): CancelConditions {
        $carCancelConditions = $this->supplierAdapter->getCarCancelConditions($serviceId, $carId, $bookingDate);
        if ($carCancelConditions === null) {
            throw new NotFoundServiceCancelConditions();
        }

        $newCancelConditions = $this->buildCarCancelConditions($carCancelConditions, $pricePerCar, $countCars, $bookingDate);
        if ($cancelConditions === null) {
            return $newCancelConditions;
        }

        return $this->mergeCancelConditions($cancelConditions, $newCancelConditions);
    }

    private function buildCarCancelConditions(
        CancelConditionsDto $carCancelConditions,
        float $pricePerCar,
        int $countCars,
        \DateTimeInterface $bookingDate
    ): CancelConditions {
        $cancelNoFeeDate = null;
        $dailyMarkupOptions = [];
        $maxDaysCount = Arr::first($carCancelConditions->dailyMarkups)?->daysCount;

        $allCarsPrice = $pricePerCar * $countCars;
        if ($maxDaysCount !== null && $bookingDate !== null) {
            $cancelNoFeeDate = $bookingDate->clone()->subDays($maxDaysCount)->toImmutable();
            $dailyMarkupOptions = collect($carCancelConditions->dailyMarkups)->map(
                fn(DailyMarkupDto $dailyMarkupDto) => new DailyCancelFeeValue(
                    value: FeeValue::createAbsolute(
                        $this->calculatePercentValue($dailyMarkupDto->percent, $allCarsPrice)
                    ),
                    cancelPeriodType: CancelFeePeriodTypeEnum::FULL_PERIOD,
                    daysCount: $dailyMarkupDto->daysCount
                )
            );
        }

        return new CancelConditions(
            noCheckInMarkup: new CancelFeeValue(
                value: FeeValue::createAbsolute(
                    $this->calculatePercentValue($carCancelConditions->noCheckInMarkup->percent, $allCarsPrice)
                ),
                cancelPeriodType: CancelFeePeriodTypeEnum::FULL_PERIOD
            ),
            dailyMarkups: new DailyCancelFeeValueCollection($dailyMarkupOptions),
            cancelNoFeeDate: $cancelNoFeeDate
        );
    }

    private function mergeCancelConditions(
        CancelConditions $baseCancelConditions,
        CancelConditions $carCancelConditions
    ): CancelConditions {
        //@todo merge cancelConditions
    }

    private function calculatePercentValue(int $percent, float $value): float
    {
        return $value * $percent / 100;
    }
}
