<?php

namespace Module\Booking\Shared\Domain\Booking\Service\EventMapper;

use Module\Booking\Shared\Domain\Guest\Repository\GuestRepositoryInterface;
use Sdk\Booking\Event\HotelBooking\GuestBinded;
use Sdk\Booking\IntegrationEvent\HotelBooking\GuestAdded;
use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Shared\Contracts\Event\IntegrationEventMapperInterface;

class AccommodationGuestBindedMapper implements IntegrationEventMapperInterface
{
    public function __construct(
        private readonly GuestRepositoryInterface $guestRepository,
    ) {}

    public function map(DomainEventInterface $event): GuestAdded
    {
        assert($event instanceof GuestBinded);

        $guest = $this->guestRepository->findOrFail($event->guestId);

        return new GuestAdded(
            $event->bookingId()->value(),
            $event->accommodation->id()->value(),
            $event->accommodation->roomInfo()->name(),
            $guest->id()->value(),
            $guest->fullName()
        );
    }
}