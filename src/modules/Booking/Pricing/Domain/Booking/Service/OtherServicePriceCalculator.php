<?php

namespace Module\Booking\Pricing\Domain\Booking\Service;

use Module\Booking\Moderation\Application\Exception\NotFoundServicePriceException;
use Module\Booking\Shared\Domain\Booking\Adapter\SupplierAdapterInterface;
use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Entity\Other;
use Module\Booking\Shared\Domain\Booking\Repository\DetailsRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingPriceItem;
use Module\Booking\Shared\Domain\Booking\ValueObject\BookingPrices;

class OtherServicePriceCalculator implements PriceCalculatorInterface
{
    public function __construct(
        private readonly DetailsRepositoryInterface $detailsRepository,
        private readonly SupplierAdapterInterface $supplierAdapter,
    ) {
    }

    public function calculate(Booking $booking): BookingPrices
    {
        /** @var Other $details */
        $details = $this->detailsRepository->findOrFail($booking->id());
        $servicePrice = $this->supplierAdapter->getOtherServicePrice(
            $details->serviceInfo()->supplierId(),
            $details->serviceInfo()->id(),
            $booking->prices()->clientPrice()->currency(),
            now()//@todo какая дата услуги?
        );
        if ($servicePrice === null) {
            throw new NotFoundServicePriceException();
        }

        return new BookingPrices(
            new BookingPriceItem(
                currency: $servicePrice->supplierPrice->currency,
                calculatedValue: $servicePrice->supplierPrice->amount,
                manualValue: $booking->prices()->supplierPrice()->manualValue(),
                penaltyValue: $booking->prices()->supplierPrice()->penaltyValue()
            ),
            new BookingPriceItem(
                currency: $servicePrice->clientPrice->currency,
                calculatedValue: $servicePrice->clientPrice->amount,
                manualValue: $booking->prices()->clientPrice()->manualValue(),
                penaltyValue: $booking->prices()->clientPrice()->penaltyValue()
            )
        );
    }
}