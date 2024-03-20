<?php

namespace Module\Booking\Shared\Domain\Booking\Service\IntegrationEventMapper\Hotel;

use Module\Booking\Shared\Domain\Booking\Repository\AccommodationRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Repository\BookingRepositoryInterface;
use Module\Booking\Shared\Domain\Booking\Service\IntegrationEventMapper\MapperInterface;
use Module\Booking\Shared\Domain\Guest\Event\GuestModified;
use Sdk\Booking\IntegrationEvent\HotelBooking\GuestEdited as IntegrationEvent;
use Sdk\Module\Contracts\Event\DomainEventInterface;

class AccommodationGuestEditedMapper implements MapperInterface
{
    public function __construct(
        private readonly BookingRepositoryInterface $bookingRepository,
        private readonly AccommodationRepositoryInterface $accommodationRepository,
    ) {}

    public function map(DomainEventInterface $event): array
    {
        assert($event instanceof GuestModified);

        $events = [];
        $bookings = $this->bookingRepository->getByGuestId($event->guestId());
        foreach ($bookings as $booking) {
            $accommodations = $this->accommodationRepository->getByBookingId($booking->id());
            foreach ($accommodations as $accommodation) {
                if (!$accommodation->guestIds()->has($event->guestId())) {
                    continue;
                }

                $events[] = new IntegrationEvent(
                    $booking->id()->value(),
                    $accommodation->id()->value(),
                    $accommodation->roomInfo()->name(),
                    $event->guest()->id()->value(),
                    $event->guest()->fullName(),
                    $event->guestBefore()->fullName(),
                );
            }
        }

        return $events;
    }
}
