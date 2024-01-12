<?php

namespace Pkg\Booking\Requesting\Domain\Service\ChangesRegistrar;

use Pkg\Booking\Requesting\Domain\Entity\Changes;
use Pkg\Booking\Requesting\Domain\ValueObject\ChangesIdentifier;
use Sdk\Booking\IntegrationEvent\BookingEventInterface;
use Sdk\Booking\IntegrationEvent\HotelBooking\AccommodationAdded;

class AccommodationAddedRegistrar extends AbstractRegistrar
{
    public function register(BookingEventInterface $event): void
    {
        assert($event instanceof AccommodationAdded);

        $identifier = new ChangesIdentifier(
            $event->bookingId,
            "accommodation[$event->accommodationId]"
        );
        $currentChanges = $this->changesStorage->find($identifier);

        if (!$currentChanges) {
            $currentChanges = Changes::makeCreated(
                $identifier,
                "Добавлено размещение \"$event->roomName\"",
                $this->eventPayload($event)
            );
        } else {
            //@todo compare details
        }

        $this->changesStorage->store($currentChanges);
    }

    private function buildDetailsDescription($event): string
    {
        return '';
    }

    private function eventPayload($event): array
    {
        return [
            'roomId' => $event->roomId,
            'roomName' => $event->roomName,
        ];
    }
}
