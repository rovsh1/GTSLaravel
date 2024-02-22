<?php

namespace Pkg\Booking\Requesting\Domain\Service\ChangesRegistrar;

use Pkg\Booking\Requesting\Domain\Entity\Changes;
use Pkg\Booking\Requesting\Domain\ValueObject\ChangesIdentifier;
use Sdk\Booking\IntegrationEvent\BookingEventInterface;
use Sdk\Booking\IntegrationEvent\HotelBooking\GuestEdited;

class GuestEditedRegistrar extends AbstractRegistrar
{
    public function register(BookingEventInterface $event): void
    {
        assert($event instanceof GuestEdited);

        $identifier = new ChangesIdentifier(
            $event->bookingId,
            "accommodation[$event->accommodationId].guests"
        );
        $currentChanges = $this->changesStorage->find($identifier);

        if (!$currentChanges) {
            $currentChanges = Changes::makeCreated(
                $identifier,
                $this->buildDetailsDescription($event),
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

    private function buildDetailsDescription(GuestEdited $event): string
    {
        return "Изменен гость $event->oldGuestName " . self::DIFF_SEPARATOR . " $event->guestName";
    }

    private function eventPayload(GuestEdited $event): array
    {
        return [
            'guestId' => $event->guestId,
            'guestName' => $event->guestName,
            'oldGuestName' => $event->oldGuestName,
            'roomName' => $event->roomName,
            'accommodationId' => $event->accommodationId,
        ];
    }
}
