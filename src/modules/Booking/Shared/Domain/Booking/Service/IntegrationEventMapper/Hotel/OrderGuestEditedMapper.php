<?php

namespace Module\Booking\Shared\Domain\Booking\Service\IntegrationEventMapper\Hotel;

use Module\Booking\Shared\Domain\Booking\DbContext\CarBidDbContextInterface;
use Module\Booking\Shared\Domain\Booking\Repository\AccommodationRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Service\IntegrationEventMapper\MapperInterface;
use Module\Booking\Shared\Domain\Guest\Event\GuestModified;
use Sdk\Booking\IntegrationEvent\HotelBooking\GuestEdited as HotelIntegrationEvent;
use Sdk\Booking\IntegrationEvent\AirportBooking\GuestEdited as AirportIntegrationEvent;
use Sdk\Booking\IntegrationEvent\TransferBooking\GuestEdited as TransferIntegrationEvent;
use Sdk\Module\Contracts\Event\DomainEventInterface;

class OrderGuestEditedMapper implements MapperInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly AccommodationRepositoryInterface $accommodationRepository,
        private readonly CarBidDbContextInterface $carBidDbContext,
    ) {}

    public function map(DomainEventInterface $event): array
    {
        assert($event instanceof GuestModified);

        $events = [];
        $bookings = $this->bookingRepository->getByGuestId($event->guestId());
        foreach ($bookings as $booking) {
            if ($booking->serviceType()->isAirportService()) {
                $events[] = new AirportIntegrationEvent(
                    $booking->id()->value(),
                    $event->guest()->id()->value(),
                    $event->guest()->fullName(),
                    $event->guestBefore()->fullName(),
                );
                continue;
            }

            $accommodations = $this->accommodationRepository->getByBookingId($booking->id());
            foreach ($accommodations as $accommodation) {
                if (!$accommodation->guestIds()->has($event->guestId())) {
                    continue;
                }

                $events[] = new HotelIntegrationEvent(
                    $booking->id()->value(),
                    $accommodation->id()->value(),
                    $accommodation->roomInfo()->name(),
                    $event->guest()->id()->value(),
                    $event->guest()->fullName(),
                    $event->guestBefore()->fullName(),
                );
            }

            $carBids = $this->carBidDbContext->getByBookingId($booking->id());
            foreach ($carBids as $carBid) {
                if (!$carBid->guestIds()->has($event->guestId())) {
                    continue;
                }

                $events[] = new TransferIntegrationEvent(
                    $booking->id()->value(),
                    $carBid->id()->value(),
                    $event->guest()->id()->value(),
                    $event->guest()->fullName(),
                    $event->guestBefore()->fullName(),
                );
            }
        }

        return $events;
    }
}
