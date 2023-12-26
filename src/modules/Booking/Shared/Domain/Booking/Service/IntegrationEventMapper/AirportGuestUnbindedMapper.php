<?php

namespace Module\Booking\Shared\Domain\Booking\Service\IntegrationEventMapper;

use Module\Booking\Shared\Domain\Guest\Repository\GuestRepositoryInterface;
use Sdk\Booking\Event\ServiceBooking\GuestUnbinded;
use Sdk\Booking\IntegrationEvent\AirportBooking\GuestRemoved;
use Sdk\Module\Contracts\Event\DomainEventInterface;

class AirportGuestUnbindedMapper implements MapperInterface
{
    public function __construct(
        private readonly GuestRepositoryInterface $guestRepository,
    ) {}

    public function map(DomainEventInterface $event): array
    {
        assert($event instanceof GuestUnbinded);

        $guest = $this->guestRepository->findOrFail($event->guestId);

        return [
            new GuestRemoved(
                $event->bookingId()->value(),
                $guest->id()->value(),
                $guest->fullName()
            )
        ];
    }
}