<?php

namespace Module\Booking\Pricing\Domain\Booking\Service;

use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\DbContext\CarBidDbContextInterface;
use Module\Booking\Shared\Domain\Booking\Repository\DetailsRepositoryInterface;
use Sdk\Booking\Entity\CarBid;
use Sdk\Booking\Entity\Details\CarRentWithDriver;
use Sdk\Booking\ValueObject\BookingPriceItem;
use Sdk\Booking\ValueObject\BookingPrices;

class TransferServicePriceCalculator implements PriceCalculatorInterface
{
    public function __construct(
        private readonly DetailsRepositoryInterface $detailsRepository,
        private readonly CarBidDbContextInterface $carBidDbContext,
    ) {
    }

    public function calculate(Booking $booking): BookingPrices
    {
        $details = $this->detailsRepository->findOrFail($booking->id());
        $bookingPrices = $booking->prices();

        $reducer = function (array $data, CarBid $carBid) use ($details) {
            $data['clientPriceAmount'] += $carBid->clientPriceValue();
            $data['supplierPriceAmount'] += $carBid->supplierPriceValue();
            if ($details instanceof CarRentWithDriver) {
                $data['clientPriceAmount'] *= $details->bookingPeriod()?->daysCount() ?? 1;
                $data['supplierPriceAmount'] *= $details->bookingPeriod()?->daysCount() ?? 1;
            }

            return $data;
        };

        $carBids = $this->carBidDbContext->getByBookingId($booking->id());
        ['clientPriceAmount' => $clientPriceAmount, 'supplierPriceAmount' => $supplierPriceAmount] = collect($carBids->all())
            ->reduce($reducer, ['clientPriceAmount' => 0, 'supplierPriceAmount' => 0]);

        return new BookingPrices(
            new BookingPriceItem(
                currency: $bookingPrices->supplierPrice()->currency(),
                calculatedValue: $supplierPriceAmount,
                manualValue: $bookingPrices->supplierPrice()->manualValue(),
                penaltyValue: $bookingPrices->supplierPrice()->penaltyValue()
            ),
            new BookingPriceItem(
                currency: $bookingPrices->clientPrice()->currency(),
                calculatedValue: $clientPriceAmount,
                manualValue: $bookingPrices->clientPrice()->manualValue(),
                penaltyValue: $bookingPrices->clientPrice()->penaltyValue()
            )
        );
    }
}
