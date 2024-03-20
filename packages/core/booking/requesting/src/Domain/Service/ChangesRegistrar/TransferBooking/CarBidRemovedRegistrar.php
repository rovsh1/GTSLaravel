<?php

namespace Pkg\Booking\Requesting\Domain\Service\ChangesRegistrar\TransferBooking;

use Pkg\Booking\Requesting\Domain\Entity\Changes;
use Pkg\Booking\Requesting\Domain\Service\ChangesRegistrar\AbstractRegistrar;
use Pkg\Booking\Requesting\Domain\ValueObject\ChangesIdentifier;
use Sdk\Booking\IntegrationEvent\BookingEventInterface;
use Sdk\Booking\IntegrationEvent\TransferBooking\CarBidRemoved;
use Sdk\Booking\ValueObject\BookingId;

class CarBidRemovedRegistrar extends AbstractRegistrar
{
    public function register(BookingEventInterface $event): void
    {
        assert($event instanceof CarBidRemoved);

        $identifier = new ChangesIdentifier($event->bookingId, "carBid[$event->carBidId]");
        $currentChanges = $this->changesStorage->find($identifier);

        if (!$currentChanges) {
            $this->changesStorage->store(
                Changes::makeDeleted(
                    $identifier,
                    "Автомобиль \"$event->carName\" удален"
                )
            );
        } elseif ($currentChanges->isCreated()) {
            $this->changesStorage->remove($identifier);
        } else {
            $currentChanges->setDeleted();
            $currentChanges->setDescription("Автомобиль \"$event->carName\" удален");
            $this->changesStorage->store($currentChanges);
        }

        $changes = $this->changesStorage->get(new BookingId($event->bookingId), "carBid[$event->carBidId].");
        foreach ($changes as $change) {
            $this->changesStorage->remove($change->identifier());
        }
    }
}
