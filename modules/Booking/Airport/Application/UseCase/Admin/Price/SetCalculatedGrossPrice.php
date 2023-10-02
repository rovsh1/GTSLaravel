<?php

declare(strict_types=1);

namespace Module\Booking\Airport\Application\UseCase\Admin\Price;

use Module\Booking\Airport\Domain\Booking\Service\PriceCalculator\BookingCalculator;
use Module\Booking\Airport\Infrastructure\Repository\BookingRepository;
use Module\Booking\Common\Domain\Service\BookingUpdater;
use Sdk\Module\Contracts\UseCase\UseCaseInterface;

class SetCalculatedGrossPrice implements UseCaseInterface
{
    public function __construct(
        private readonly BookingRepository $repository,
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
