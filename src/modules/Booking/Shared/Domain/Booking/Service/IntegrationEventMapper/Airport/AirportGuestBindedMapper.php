<?php

namespace Module\Booking\Shared\Domain\Booking\Service\IntegrationEventMapper\Airport;

use Module\Booking\Shared\Domain\Booking\Service\IntegrationEventMapper\MapperInterface;
use Module\Booking\Shared\Domain\Guest\Repository\GuestRepositoryInterface;
use Sdk\Booking\Event\ServiceBooking\GuestBinded;
use Sdk\Booking\IntegrationEvent\AirportBooking\GuestAdded;
use Sdk\Module\Contracts\Event\DomainEventInterface;

class AirportGuestBindedMapper implements MapperInterface
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
                $guest->id()->value(),
                $guest->fullName()
            )
        ];
    }
}
