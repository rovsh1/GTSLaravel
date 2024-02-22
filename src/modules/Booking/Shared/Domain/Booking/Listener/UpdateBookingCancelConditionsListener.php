<?php

namespace Module\Booking\Shared\Domain\Booking\Listener;

use Module\Booking\Moderation\Domain\Booking\Factory\CancelConditionsFactory;
use Module\Booking\Moderation\Domain\Booking\Factory\HotelCancelConditionsFactory;
use Module\Booking\Moderation\Domain\Booking\Factory\TransferCancelConditionsFactory;
use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\DbContext\CarBidDbContextInterface;
use Module\Booking\Shared\Domain\Booking\Service\BookingUnitOfWorkInterface;
use Sdk\Booking\Contracts\Entity\DetailsInterface;
use Sdk\Booking\Contracts\Entity\TransferDetailsInterface;
use Sdk\Booking\Entity\Details\HotelBooking;
use Sdk\Booking\Event\BookingDateChangedEventInterface;
use Sdk\Booking\ValueObject\ServiceId;
use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Module\Contracts\Event\DomainEventListenerInterface;

class UpdateBookingCancelConditionsListener implements DomainEventListenerInterface
{
    public function __construct(
        private readonly BookingUnitOfWorkInterface $bookingUnitOfWork,
        private readonly TransferCancelConditionsFactory $transferCancelConditionsFactory,
        private readonly HotelCancelConditionsFactory $hotelCancelConditionsFactory,
        private readonly CancelConditionsFactory $cancelConditionsFactory,
        private readonly CarBidDbContextInterface $carBidDbContext,
    ) {}

    public function handle(DomainEventInterface $event): void
    {
        assert($event instanceof BookingDateChangedEventInterface);

        $booking = $this->bookingUnitOfWork->findOrFail($event->bookingId());
        $details = $this->bookingUnitOfWork->getDetails($event->bookingId());

        if ($details instanceof TransferDetailsInterface) {
            $this->processTransferBooking($booking, $details);
        } elseif ($details instanceof HotelBooking) {
            $this->processHotelBooking($booking, $details);
        } else {
            $this->processServiceBooking($booking, $details);
        }

        $this->bookingUnitOfWork->persist($booking);
        $this->bookingUnitOfWork->commit();
    }

    private function processTransferBooking(Booking $booking, TransferDetailsInterface $details): void
    {
        $carBids = $this->carBidDbContext->getByBookingId($booking->id());
        $cancelConditions = $this->transferCancelConditionsFactory->build(
            $details->serviceInfo()->id(),
            $carBids,
            $details->serviceDate()
        );
        $booking->setCancelConditions($cancelConditions);
    }

    private function processHotelBooking(Booking $booking, HotelBooking $details): void
    {
        $cancelConditions = $this->hotelCancelConditionsFactory->build(
            $details->hotelInfo()->id(),
            $details->bookingPeriod()
        );
        $booking->setCancelConditions($cancelConditions);
    }

    private function processServiceBooking(Booking $booking, DetailsInterface $details): void
    {
        $cancelConditions = $this->cancelConditionsFactory->build(
            new ServiceId($details->serviceInfo()->id()),
            $details->serviceType(),
            $details->serviceDate()
        );
        $booking->setCancelConditions($cancelConditions);
    }
}
