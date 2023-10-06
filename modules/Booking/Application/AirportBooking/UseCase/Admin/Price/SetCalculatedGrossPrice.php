<?php

declare(strict_types=1);

namespace Module\Booking\Application\AirportBooking\UseCase\Admin\Price;

use Module\Booking\Domain\AirportBooking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\AirportBooking\Service\PriceCalculator\BookingCalculator;
use Module\Booking\Domain\Shared\Service\BookingUpdater;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class SetCalculatedGrossPrice implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $repository,
        private readonly BookingUpdater $bookingUpdater,
        private readonly BookingCalculator $bookingCalculator
    ) {}

    public function execute(int $bookingId): void
    {
        $booking = $this->repository->find($bookingId);
        $booking->setCalculatedGrossPrice($this->bookingCalculator);
        $this->bookingUpdater->storeIfHasEvents($booking);
    }
}
