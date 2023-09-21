<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Application\Factory;

use Carbon\CarbonInterface;
use Module\Booking\Airport\Domain\Repository\CancelConditionsRepositoryInterface;
use Module\Booking\Common\Domain\ValueObject\CancelConditions;

class CancelConditionsFactory
{
    public function __construct(
        private readonly CancelConditionsRepositoryInterface $cancelConditionsRepository
    ) {}

    public function build(CarbonInterface $bookingDate): CancelConditions
    {
        $baseCancelConditions = $this->cancelConditionsRepository->get();
        $cancelNoFeeDate = null;
        $maxDaysCount = $baseCancelConditions->dailyMarkups()->first()->daysCount();
        if ($maxDaysCount !== null) {
            $cancelNoFeeDate = $bookingDate->clone()->subDays($maxDaysCount)->toImmutable();
        }

        return new CancelConditions(
            noCheckInMarkup: $baseCancelConditions->noCheckInMarkup(),
            dailyMarkups: $baseCancelConditions->dailyMarkups(),
            cancelNoFeeDate: $cancelNoFeeDate
        );
    }
}
