<?php

namespace Module\Booking\Shared\Domain\Booking\Service\EventMapper;

use Module\Booking\Shared\Domain\Guest\Repository\GuestRepositoryInterface;
use Sdk\Booking\Event\TransferBooking\GuestBinded;
use Sdk\Booking\IntegrationEvent\TransferBooking\GuestAdded;
use Sdk\Module\Contracts\Event\DomainEventInterface;
use Sdk\Shared\Contracts\Event\IntegrationEventMapperInterface;

class TransferGuestBindedMapper implements IntegrationEventMapperInterface
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
            $event->carBid->id()->value(),
            $event->carBid->carId()->value(),
            $guest->id()->value(),
            $guest->fullName()
        );
    }
}