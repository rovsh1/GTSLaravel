<?php

namespace Module\Booking\Pricing\Domain\Booking\Service;

use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\DbContext\CarBidDbContextInterface;
use Module\Booking\Shared\Domain\Booking\Repository\DetailsRepositoryInterface;
use Sdk\Booking\Entity\CarBid;
use Sdk\Booking\Entity\Details\CarRentWithDriver;
use Sdk\Booking\ValueObject\BookingPriceItem;
use Sdk\Booking\ValueObject\BookingPrices;
use Sdk\Shared\ValueObject\Money;

class TransferServicePriceCalculator implements PriceCalculatorInterface
{
    public function __construct(
        private readonly DetailsRepositoryInterface $detailsRepository,
        private readonly CarBidDbContextInterface $carBidDbContext,
    ) {}

    public function calculate(Booking $booking): BookingPrices
    {
        $details = $this->detailsRepository->findOrFail($booking->id());
        $bookingPrices = $booking->prices();

        $daysCount = 1;
        if ($details instanceof CarRentWithDriver) {
            $daysCount = $details->bookingPeriod()?->daysCount() ?? 1;
        }
        $reducer = function (array $data, CarBid $carBid) use ($daysCount) {
            $data['clientPriceAmount'] += $carBid->clientPriceValue();
            $data['supplierPriceAmount'] += $carBid->supplierPriceValue();
            if ($daysCount > 1) {
                $data['clientPriceAmount'] *= $daysCount;
                $data['supplierPriceAmount'] *= $daysCount;
            }

            return $data;
        };

        $carBids = $this->carBidDbContext->getByBookingId($booking->id());
        ['clientPriceAmount' => $clientPriceAmount, 'supplierPriceAmount' => $supplierPriceAmount] = collect(
            $carBids->all()
        )
            ->reduce($reducer, ['clientPriceAmount' => 0, 'supplierPriceAmount' => 0]);

        $supplierCurrency = $bookingPrices->supplierPrice()->currency();
        $clientCurrency = $bookingPrices->clientPrice()->currency();

        return new BookingPrices(
            new BookingPriceItem(
                currency: $supplierCurrency,
                calculatedValue: Money::round($supplierCurrency, $supplierPriceAmount),
                manualValue: Money::roundNullable($supplierCurrency, $bookingPrices->supplierPrice()->manualValue()),
                penaltyValue: Money::roundNullable($supplierCurrency, $bookingPrices->supplierPrice()->penaltyValue())
            ),
            new BookingPriceItem(
                currency: $clientCurrency,
                calculatedValue: Money::round($clientCurrency, $clientPriceAmount),
                manualValue: Money::roundNullable($clientCurrency, $bookingPrices->clientPrice()->manualValue()),
                penaltyValue: Money::roundNullable($clientCurrency, $bookingPrices->clientPrice()->penaltyValue())
            )
        );
    }
}
