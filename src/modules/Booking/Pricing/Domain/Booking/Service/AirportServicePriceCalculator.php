<?php

namespace Module\Booking\Pricing\Domain\Booking\Service;

use Carbon\Carbon;
use Module\Booking\Moderation\Application\Exception\NotFoundServicePriceException;
use Module\Booking\Shared\Domain\Booking\Adapter\SupplierAdapterInterface;
use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Entity\AirportDetailsInterface;
use Module\Booking\Shared\Domain\Booking\Repository\DetailsRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingPriceItem;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingPrices;

class AirportServicePriceCalculator implements PriceCalculatorInterface
{
    public function __construct(
        private readonly DetailsRepositoryInterface $detailsRepository,
        private readonly SupplierAdapterInterface $supplierAdapter,
    ) {
    }

    public function calculate(Booking $booking): BookingPrices
    {
        $details = $this->detailsRepository->findOrFail($booking->id());
        assert($details instanceof AirportDetailsInterface);
        $bookingPrices = $booking->prices();
        $servicePrice = $this->supplierAdapter->getAirportServicePrice(
            $details->serviceInfo()->supplierId(),
            $details->serviceInfo()->id(),
            $bookingPrices->clientPrice()->currency(),
            new Carbon($details->serviceDate())
        );
        if ($servicePrice === null) {
            throw new NotFoundServicePriceException();
        }

        return new BookingPrices(
            new BookingPriceItem(
                currency: $servicePrice->supplierPrice->currency,
                calculatedValue: $servicePrice->supplierPrice->amount * $details->guestsCount(),
                manualValue: $bookingPrices->supplierPrice()->manualValue(),
                penaltyValue: $bookingPrices->supplierPrice()->penaltyValue()
            ),
            new BookingPriceItem(
                currency: $servicePrice->clientPrice->currency,
                calculatedValue: $servicePrice->clientPrice->amount * $details->guestsCount(),
                manualValue: $bookingPrices->clientPrice()->manualValue(),
                penaltyValue: $bookingPrices->clientPrice()->penaltyValue()
            )
        );
    }
}