<?php

namespace Module\Booking\Pricing\Domain\Booking\Service;

use Carbon\Carbon;
use Module\Booking\Moderation\Application\Exception\NotFoundServicePriceException;
use Module\Booking\Shared\Domain\Booking\Adapter\SupplierAdapterInterface;
use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Repository\DetailsRepositoryInterface;
use Sdk\Booking\Contracts\Entity\AirportDetailsInterface;
use Sdk\Booking\ValueObject\BookingPriceItem;
use Sdk\Booking\ValueObject\BookingPrices;
use Sdk\Shared\ValueObject\Money;

class AirportServicePriceCalculator implements PriceCalculatorInterface
{
    public function __construct(
        private readonly DetailsRepositoryInterface $detailsRepository,
        private readonly SupplierAdapterInterface $supplierAdapter,
    ) {}

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
        $supplierCurrency = $servicePrice->supplierPrice->currency;
        $clientCurrency = $servicePrice->clientPrice->currency;

        return new BookingPrices(
            new BookingPriceItem(
                currency: $supplierCurrency,
                calculatedValue: Money::round(
                    $supplierCurrency,
                    $servicePrice->supplierPrice->amount * $details->guestsCount()
                ),
                manualValue: Money::round($supplierCurrency, $bookingPrices->supplierPrice()->manualValue()),
                penaltyValue: Money::round($supplierCurrency, $bookingPrices->supplierPrice()->penaltyValue())
            ),
            new BookingPriceItem(
                currency: $clientCurrency,
                calculatedValue: Money::round(
                    $clientCurrency,
                    $servicePrice->clientPrice->amount * $details->guestsCount()
                ),
                manualValue: Money::round($clientCurrency, $bookingPrices->clientPrice()->manualValue()),
                penaltyValue: Money::round($clientCurrency, $bookingPrices->clientPrice()->penaltyValue())
            )
        );
    }
}
