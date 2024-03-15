<?php

namespace Module\Booking\Shared\Domain\Booking\Service\IntegrationEventMapper\Transfer;

use Module\Booking\Shared\Domain\Booking\Service\IntegrationEventMapper\MapperInterface;
use Module\Booking\Shared\Domain\Guest\Repository\GuestRepositoryInterface;
use Sdk\Booking\Event\TransferBooking\GuestUnbinded;
use Sdk\Booking\IntegrationEvent\TransferBooking\GuestRemoved;
use Sdk\Module\Contracts\Event\DomainEventInterface;

class TransferGuestUnbindedMapper implements MapperInterface
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
                $event->carBid->id()->value(),
                $event->carBid->carId()->value(),
                $guest->id()->value(),
                $guest->fullName()
            )
        ];
    }
}
