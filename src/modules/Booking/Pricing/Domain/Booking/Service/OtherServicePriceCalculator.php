<?php

namespace Module\Booking\Pricing\Domain\Booking\Service;

use Carbon\Carbon;
use Module\Booking\Moderation\Application\Exception\NotFoundServicePriceException;
use Module\Booking\Shared\Domain\Booking\Adapter\SupplierAdapterInterface;
use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Repository\DetailsRepositoryInterface;
use Sdk\Booking\Entity\Details\Other;
use Sdk\Booking\ValueObject\BookingPriceItem;
use Sdk\Booking\ValueObject\BookingPrices;
use Sdk\Shared\ValueObject\Money;

class OtherServicePriceCalculator implements PriceCalculatorInterface
{
    public function __construct(
        private readonly DetailsRepositoryInterface $detailsRepository,
        private readonly SupplierAdapterInterface $supplierAdapter,
    ) {}

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
        $supplierCurrency = $servicePrice->supplierPrice->currency;
        $clientCurrency = $servicePrice->clientPrice->currency;

        return new BookingPrices(
            new BookingPriceItem(
                currency: $supplierCurrency,
                calculatedValue: Money::round($supplierCurrency, $servicePrice->supplierPrice->amount),
                manualValue: Money::round($supplierCurrency, $booking->prices()->supplierPrice()->manualValue()),
                penaltyValue: Money::round($supplierCurrency, $booking->prices()->supplierPrice()->penaltyValue())
            ),
            new BookingPriceItem(
                currency: $clientCurrency,
                calculatedValue: Money::round($clientCurrency, $servicePrice->clientPrice->amount),
                manualValue: Money::round($clientCurrency, $booking->prices()->clientPrice()->manualValue()),
                penaltyValue: Money::round($clientCurrency, $booking->prices()->clientPrice()->penaltyValue())
            )
        );
    }
}
