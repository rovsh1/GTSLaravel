<?php

namespace Pkg\Booking\Requesting\Domain\Service\ChangesRegistrar\TransferBooking;

use Pkg\Booking\Requesting\Domain\Entity\Changes;
use Pkg\Booking\Requesting\Domain\Service\ChangesRegistrar\AbstractRegistrar;
use Pkg\Booking\Requesting\Domain\ValueObject\ChangesIdentifier;
use Sdk\Booking\IntegrationEvent\BookingEventInterface;
use Sdk\Booking\IntegrationEvent\TransferBooking\GuestAdded;

class GuestAddedRegistrar extends AbstractRegistrar
{
    public function register(BookingEventInterface $event): void
    {
        assert($event instanceof GuestAdded);

        $identifier = new ChangesIdentifier(
            $event->bookingId,
//            "carBid[$event->bidId].guests",
            'guests'
        );
        $currentChanges = $this->changesStorage->find($identifier);

        if (!$currentChanges) {
            $currentChanges = Changes::makeCreated(
                $identifier,
                "Добавлен гость $event->guestName",
                $this->eventPayload($event)
            );
        } elseif ($currentChanges->isDeleted()) {
            $currentChanges->setUpdated();
            $currentChanges->setDescription("Гость заменен на $event->guestName");
        } else {
            //@todo compare details
        }

        $this->changesStorage->store($currentChanges);
    }

    private function buildDetailsDescription($event): string
    {
        return '';
    }

    private function eventPayload(GuestAdded $event): array
    {
        return [
            'guestId' => $event->guestId,
            'guestName' => $event->guestName,
            'carId' => $event->carId,
            'bidId' => $event->bidId,
        ];
    }
}