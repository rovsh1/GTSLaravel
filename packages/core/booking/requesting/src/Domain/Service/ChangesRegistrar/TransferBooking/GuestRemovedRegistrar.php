<?php

namespace Pkg\Booking\Requesting\Domain\Service\ChangesRegistrar\TransferBooking;

use Pkg\Booking\Requesting\Domain\Entity\Changes;
use Pkg\Booking\Requesting\Domain\Service\ChangesRegistrar\AbstractRegistrar;
use Pkg\Booking\Requesting\Domain\ValueObject\ChangesIdentifier;
use Sdk\Booking\IntegrationEvent\BookingEventInterface;
use Sdk\Booking\IntegrationEvent\TransferBooking\GuestRemoved;

class GuestRemovedRegistrar extends AbstractRegistrar
{
    public function register(BookingEventInterface $event): void
    {
        assert($event instanceof GuestRemoved);

        $identifier = new ChangesIdentifier(
            $event->bookingId,
            "carBid[$event->carBidId].guests"
        );
        $currentChanges = $this->changesStorage->find($identifier);

        if (!$currentChanges) {
            $this->changesStorage->store(
                Changes::makeDeleted(
                    $identifier,
                    "Гость $event->guestName удален",
                    $this->eventPayload($event)
                )
            );
        } elseif ($currentChanges->isCreated()) {
            $this->changesStorage->remove($identifier);
        } else {
            $currentChanges->setDeleted();
            $currentChanges->setDescription("Гость $event->guestName удален");
            $this->changesStorage->store($currentChanges);
        }
    }

    private function buildDetailsDescription($event): string
    {
        return '';
    }

    private function eventPayload(GuestRemoved $event): array
    {
        return [
            'guestId' => $event->guestId,
            'guestName' => $event->guestName,
            'bidId' => $event->carBidId,
            'carId' => $event->carId,
        ];
    }
}
