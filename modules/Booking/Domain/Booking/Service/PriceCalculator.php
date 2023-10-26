<?php

declare(strict_types=1);

namespace Module\Booking\Domain\Booking\Service;

use Carbon\Carbon;
use Module\Booking\Application\AirportBooking\Exception\NotFoundServicePriceException;
use Module\Booking\Domain\Booking\Adapter\SupplierAdapterInterface;
use Module\Booking\Domain\Booking\Booking;
use Module\Booking\Domain\Booking\Entity\CIPRoomInAirport;
use Module\Booking\Domain\Booking\Entity\ServiceDetailsInterface;
use Module\Booking\Domain\Booking\Factory\DetailsRepositoryFactory;
use Module\Booking\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\Booking\Service\HotelBooking\PriceCalculator\PriceCalculator as HotelBookingPriceCalculator;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Booking\ValueObject\BookingPriceItem;
use Module\Booking\Domain\Booking\ValueObject\BookingPrices;
use Module\Booking\Domain\Booking\ValueObject\CarBid;
use Module\Booking\Domain\Shared\Service\BookingUpdater;
use Module\Shared\Enum\ServiceTypeEnum;

class PriceCalculator
{
    public function __construct(
        private readonly HotelBookingPriceCalculator $hotelBookingPriceCalculator,
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly SupplierAdapterInterface $supplierAdapter,
        private readonly DetailsRepositoryFactory $detailsRepositoryFactory,
        private readonly BookingUpdater $bookingUpdater
    ) {}

    public function calculate(BookingId $bookingId): void
    {
        $booking = $this->bookingRepository->findOrFail($bookingId);
        if (in_array($booking->serviceType(), ServiceTypeEnum::getAirportCases())) {
            $this->processAirportBooking($booking);
        } elseif (in_array($booking->serviceType(), ServiceTypeEnum::getTransferCases())) {
            $this->processTransferBooking($booking);
        } elseif ($booking->serviceType() === ServiceTypeEnum::HOTEL_BOOKING) {
            $this->hotelBookingPriceCalculator->calculate($booking);
        }
    }

    private function processAirportBooking(Booking $booking): void
    {
        $repository = $this->detailsRepositoryFactory->buildByBookingId($booking->id());
        /** @var CIPRoomInAirport $details */
        $details = $repository->find($booking->id());
        $servicePrice = $this->supplierAdapter->getAirportServicePrice(
            $details->serviceInfo()->supplierId(),
            $details->serviceInfo()->id(),
            $booking->prices()->clientPrice()->currency(),
            new Carbon($details->serviceDate())
        );
        if ($servicePrice === null) {
            throw new NotFoundServicePriceException();
        }

        $bookingPrice = new BookingPrices(
            new BookingPriceItem(
                currency: $servicePrice->supplierPrice->currency,
                calculatedValue: $servicePrice->supplierPrice->amount * $details->guestsCount(),
                manualValue: $booking->prices()->supplierPrice()->manualValue(),
                penaltyValue: $booking->prices()->supplierPrice()->penaltyValue()
            ),
            new BookingPriceItem(
                currency: $servicePrice->clientPrice->currency,
                calculatedValue: $servicePrice->clientPrice->amount * $details->guestsCount(),
                manualValue: $booking->prices()->clientPrice()->manualValue(),
                penaltyValue: $booking->prices()->clientPrice()->penaltyValue()
            )
        );

        $booking->updatePrice($bookingPrice);
        //@todo сейчас не кидается ивент, в будущем заменить на storeIfHasEvents
        $this->bookingUpdater->store($booking);
    }

    private function processTransferBooking(Booking $booking): void {
        $repository = $this->detailsRepositoryFactory->buildByBookingId($booking->id());
        /** @var ServiceDetailsInterface $details */
        $details = $repository->find($booking->id());

        $reducer = function (array $data, CarBid $carBid) {
            $data['clientPriceAmount'] += $carBid->prices()->clientPrice()->totalAmount();
            $data['supplierPriceAmount'] += $carBid->prices()->supplierPrice()->totalAmount();

            return $data;
        };
        ['clientPriceAmount' => $clientPriceAmount, 'supplierPriceAmount' => $supplierPriceAmount] = collect($details->carBids()->all())
            ->reduce($reducer, ['clientPriceAmount' => 0, 'supplierPriceAmount' => 0]);

        $bookingPrice = new BookingPrices(
            new BookingPriceItem(
                currency: $booking->prices()->supplierPrice()->currency(),
                calculatedValue: $supplierPriceAmount,
                manualValue: $booking->prices()->supplierPrice()->manualValue(),
                penaltyValue: $booking->prices()->supplierPrice()->penaltyValue()
            ),
            new BookingPriceItem(
                currency: $booking->prices()->clientPrice()->currency(),
                calculatedValue: $clientPriceAmount,
                manualValue: $booking->prices()->clientPrice()->manualValue(),
                penaltyValue: $booking->prices()->clientPrice()->penaltyValue()
            )
        );

        $booking->updatePrice($bookingPrice);
        //@todo сейчас не кидается ивент, в будущем заменить на storeIfHasEvents
        $this->bookingUpdater->store($booking);
    }
}
