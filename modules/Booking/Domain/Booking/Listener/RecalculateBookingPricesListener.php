<?php

namespace Module\Booking\Domain\Booking\Listener;

use Carbon\Carbon;
use Module\Booking\Domain\Booking\Adapter\SupplierAdapterInterface;
use Module\Booking\Domain\Booking\Entity\CIPRoomInAirport;
use Module\Booking\Domain\Booking\Entity\ServiceDetailsInterface;
use Module\Booking\Domain\Booking\Event\CarBidAdded;
use Module\Booking\Domain\Booking\Event\CarBidRemoved;
use Module\Booking\Domain\Booking\Event\CarBidUpdated;
use Module\Booking\Domain\Booking\Event\GuestBinded;
use Module\Booking\Domain\Booking\Event\GuestUnbinded;
use Module\Booking\Domain\Booking\Event\PriceBecomeDeprecatedEventInterface;
use Module\Booking\Domain\Booking\Factory\DetailsRepositoryFactory;
use Module\Booking\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Domain\Booking\Service\HotelBooking\PriceCalculator\PriceCalculator;
use Module\Booking\Domain\Booking\ValueObject\BookingId;
use Module\Booking\Domain\Booking\ValueObject\BookingPriceItem;
use Module\Booking\Domain\Booking\ValueObject\BookingPrices;
use Module\Booking\Domain\Booking\ValueObject\CarBid;
use Module\Booking\Domain\Shared\Service\BookingUpdater;
use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Module\Contracts\Event\DomainEventListenerInterface;

class RecalculateBookingPricesListener implements DomainEventListenerInterface
{
    public function __construct(
        private readonly PriceCalculator $hotelBookingPriceCalculator,
        private readonly SupplierAdapterInterface $supplierAdapter,
        private readonly DetailsRepositoryFactory $detailsRepositoryFactory,
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly BookingUpdater $bookingUpdater
    ) {}

    public function handle(DomainEventInterface $event): void
    {
        assert($event instanceof PriceBecomeDeprecatedEventInterface);

        if ($event instanceof GuestBinded || $event instanceof GuestUnbinded) {
            $this->processAirportBooking($event->bookingId());

            return;
        }

        if ($event instanceof CarBidAdded || $event instanceof CarBidUpdated || $event instanceof CarBidRemoved) {
            $this->processTransferBooking($event->bookingId());

            return;
        }


        $this->hotelBookingPriceCalculator->calculate($event->bookingId());
    }

    private function processTransferBooking(BookingId $bookingId): void
    {
        $booking = $this->bookingRepository->findOrFail($bookingId);
        $repository = $this->detailsRepositoryFactory->buildByBookingId($bookingId);
        /** @var ServiceDetailsInterface $details */
        $details = $repository->find($bookingId);

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

    private function processAirportBooking(BookingId $bookingId): void
    {
        $booking = $this->bookingRepository->findOrFail($bookingId);
        $repository = $this->detailsRepositoryFactory->buildByBookingId($bookingId);
        /** @var CIPRoomInAirport $details */
        $details = $repository->find($bookingId);
        $servicePrice = $this->supplierAdapter->getAirportServicePrice(
            $details->serviceInfo()->supplierId(),
            $details->serviceInfo()->id(),
            $booking->prices()->clientPrice()->currency(),
            new Carbon($details->serviceDate())
        );

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
}
