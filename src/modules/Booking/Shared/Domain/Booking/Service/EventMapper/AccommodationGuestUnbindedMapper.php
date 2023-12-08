<?php

namespace Module\Booking\Shared\Domain\Booking\Service\EventMapper;

use Module\Booking\Shared\Domain\Guest\Repository\GuestRepositoryInterface;
use Sdk\Booking\Event\HotelBooking\GuestUnbinded;
use Sdk\Booking\IntegrationEvent\HotelBooking\GuestRemoved;
use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Shared\Contracts\Event\IntegrationEventMapperInterface;

class AccommodationGuestUnbindedMapper implements IntegrationEventMapperInterface
{
    public function __construct(
        private readonly GuestRepositoryInterface $guestRepository,
    ) {}

    public function map(DomainEventInterface $event): GuestRemoved
    {
        assert($event instanceof GuestUnbinded);

        $guest = $this->guestRepository->findOrFail($event->guestId);

        return new GuestRemoved(
            $event->bookingId()->value(),
            $event->accommodation->id()->value(),
            $event->accommodation->roomInfo()->name(),
            $guest->id()->value(),
            $guest->fullName()
        );
    }
}