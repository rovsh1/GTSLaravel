<?php

declare(strict_types=1);

namespace Module\Booking\Application\TransferBooking\UseCase\Admin\Price;

use Module\Booking\Domain\Shared\Service\BookingUpdater;
use Module\Booking\Domain\TransferBooking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\TransferBooking\Service\PriceCalculator\BookingCalculator;
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
