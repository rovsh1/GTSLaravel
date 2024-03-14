<?php

namespace Module\Booking\Shared\Domain\Booking\Service\IntegrationEventMapper\Hotel;

use Module\Booking\Shared\Domain\Booking\Service\IntegrationEventMapper\MapperInterface;
use Module\Booking\Shared\Domain\Guest\Repository\GuestRepositoryInterface;
use Sdk\Booking\Event\HotelBooking\GuestBinded;
use Sdk\Booking\IntegrationEvent\HotelBooking\GuestAdded;
use Sdk\Module\Contracts\Event\DomainEventInterface;

class AccommodationGuestBindedMapper implements MapperInterface
{
    public function __construct(
        private readonly GuestRepositoryInterface $guestRepository,
    ) {}

    public function map(DomainEventInterface $event): array
    {
        assert($event instanceof GuestBinded);

        $guest = $this->guestRepository->findOrFail($event->guestId);

        return [
            new GuestAdded(
                $event->bookingId()->value(),
                $event->accommodation->id()->value(),
                $event->accommodation->roomInfo()->name(),
                $guest->id()->value(),
                $guest->fullName()
            )
        ];
    }
}
