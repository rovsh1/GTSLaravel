<?php

namespace Module\Booking\Pricing\Domain\Booking\Service;

use Module\Booking\Shared\Domain\Booking\DbContext\BookingDbContextInterface;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Sdk\Booking\ValueObject\BookingId;

class RecalculatePriceService
{
    public function __construct(
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly BookingDbContextInterface $bookingDbContext,
        private readonly PriceCalculatorFactory $priceCalculatorFactory,
    ) {}

    public function recalculate(BookingId $bookingId): void
    {
        $booking = $this->bookingRepository->findOrFail($bookingId);
        $calculator = $this->priceCalculatorFactory->build($booking->serviceType());
        $bookingPrices = $calculator->calculate($booking);
        $booking->updatePrice($bookingPrices);
        $this->bookingDbContext->store($booking);
    }
}
