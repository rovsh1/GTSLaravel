<?php

namespace Module\Booking\Requesting\Domain\Service\ChangesRegistrar;

use Module\Booking\Requesting\Domain\Entity\Changes;
use Module\Booking\Requesting\Domain\ValueObject\ChangesIdentifier;
use Sdk\Booking\IntegrationEvent\BookingEventInterface;
use Sdk\Booking\IntegrationEvent\HotelBooking\AccommodationDeleted;

class AccommodationRemovedRegistrar extends AbstractRegistrar
{
    public function register(BookingEventInterface $event): void
    {
        assert($event instanceof AccommodationDeleted);

        $identifier = new ChangesIdentifier($event->bookingId, "accommodation[$event->accommodationId]");
        $currentChanges = $this->changesStorage->find($identifier);

        if (!$currentChanges) {
            $this->changesStorage->store(
                Changes::makeDeleted(
                    $identifier,
                    "Размещение \"$event->roomName\" удалено"
                )
            );
        } elseif ($currentChanges->isCreated()) {
            $this->changesStorage->remove($identifier);
        } else {
            $currentChanges->setDeleted();
            $currentChanges->setDescription("Размещение \"$event->roomName\" удалено");
            $this->changesStorage->store($currentChanges);
        }
    }
}
