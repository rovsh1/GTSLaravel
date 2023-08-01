<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Application\UseCase\Admin\Price;

use Module\Booking\Common\Domain\Service\BookingUpdater;
use Module\Booking\HotelBooking\Domain\Service\PriceCalculator\BookingCalculator;
use Module\Booking\HotelBooking\Infrastructure\Repository\BookingRepository;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class SetCalculatedHoPrice implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepository $repository,
        private readonly BookingUpdater $bookingUpdater,
        private readonly BookingCalculator $bookingCalculator
    ) {}

    public function execute(int $bookingId): void
    {
        $booking = $this->repository->find($bookingId);
        $booking->setCalculatedHoPrice($this->bookingCalculator);
        $this->bookingUpdater->storeIfHasEvents($booking);
    }
}
