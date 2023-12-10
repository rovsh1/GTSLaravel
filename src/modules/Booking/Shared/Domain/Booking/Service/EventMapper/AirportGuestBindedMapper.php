<?php

namespace Module\Booking\Shared\Domain\Booking\Service\EventMapper;

use Module\Booking\Shared\Domain\Guest\Repository\GuestRepositoryInterface;
use Sdk\Booking\Event\ServiceBooking\GuestBinded;
use Sdk\Booking\IntegrationEvent\AirportBooking\GuestAdded;
use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Shared\Contracts\Event\IntegrationEventMapperInterface;

class AirportGuestBindedMapper implements IntegrationEventMapperInterface
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
            $guest->id()->value(),
            $guest->fullName()
        );
    }
}