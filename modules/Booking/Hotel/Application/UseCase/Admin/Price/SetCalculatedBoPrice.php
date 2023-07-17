<?php

declare(strict_types=1);

namespace Module\Booking\Hotel\Application\UseCase\Admin\Price;

use Module\Booking\Common\Domain\Service\BookingUpdater;
use Module\Booking\Hotel\Infrastructure\Repository\BookingRepository;
use Module\Booking\PriceCalculator\Domain\Service\BookingCalculatorInterface;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class SetCalculatedBoPrice implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepository $repository,
        private readonly BookingUpdater $bookingUpdater,
        private readonly BookingCalculatorInterface $bookingCalculator
    ) {}

    public function execute(int $bookingId): void
    {
        $booking = $this->repository->find($bookingId);
        $booking->setCalculatedBoPrice($this->bookingCalculator);
        $this->bookingUpdater->store($booking);
    }
}
