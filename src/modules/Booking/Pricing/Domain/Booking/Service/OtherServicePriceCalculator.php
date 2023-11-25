<?php

namespace Module\Booking\Pricing\Domain\Booking\Service;

use Carbon\Carbon;
use Module\Booking\Moderation\Application\Exception\NotFoundServicePriceException;
use Module\Booking\Shared\Domain\Booking\Adapter\SupplierAdapterInterface;
use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Repository\DetailsRepositoryInterface;
use Sdk\Booking\Entity\BookingDetails\Other;
use Sdk\Booking\ValueObject\BookingPriceItem;
use Sdk\Booking\ValueObject\BookingPrices;

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
            new Carbon($details->serviceDate()),
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
