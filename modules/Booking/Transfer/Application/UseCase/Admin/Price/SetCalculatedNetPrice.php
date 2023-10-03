<?php

declare(strict_types=1);

namespace Module\Booking\Transfer\Application\UseCase\Admin\Price;

use Module\Booking\Transfer\Domain\Booking\Service\PriceCalculator\BookingCalculator;
use Module\Booking\Common\Domain\Service\BookingUpdater;
use Module\Booking\Transfer\Domain\Booking\Repository\BookingRepositoryInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class SetCalculatedNetPrice implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $repository,
        private readonly BookingUpdater $bookingUpdater,
        private readonly BookingCalculator $bookingCalculator
    ) {}

    public function execute(int $bookingId): void
    {
        $booking = $this->repository->find($bookingId);
        $booking->setCalculatedNetPrice($this->bookingCalculator);
        $this->bookingUpdater->storeIfHasEvents($booking);
    }
}
