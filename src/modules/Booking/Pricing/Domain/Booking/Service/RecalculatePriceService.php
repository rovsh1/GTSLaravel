<?php

namespace Module\Booking\Pricing\Domain\Booking\Service;

use Module\Booking\Shared\Domain\Booking\Service\BookingUnitOfWorkInterface;
use Sdk\Booking\ValueObject\BookingId;

class RecalculatePriceService
{
    public function __construct(
        private readonly BookingUnitOfWorkInterface $bookingUnitOfWork,
        private readonly PriceCalculatorFactory $priceCalculatorFactory,
    ) {}

    public function recalculate(BookingId $bookingId): void
    {
        $booking = $this->bookingUnitOfWork->findOrFail($bookingId);
        $calculator = $this->priceCalculatorFactory->build($booking->serviceType());
        $bookingPrices = $calculator->calculate($booking);
        $booking->updatePrice($bookingPrices);
        $this->bookingUnitOfWork->commit();
    }
}
