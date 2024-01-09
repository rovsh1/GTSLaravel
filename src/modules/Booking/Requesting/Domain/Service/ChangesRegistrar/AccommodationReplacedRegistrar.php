<?php

namespace Module\Booking\Requesting\Domain\Service\ChangesRegistrar;

use Module\Booking\Requesting\Domain\Entity\Changes;
use Module\Booking\Requesting\Domain\ValueObject\ChangesIdentifier;
use Sdk\Booking\IntegrationEvent\BookingEventInterface;
use Sdk\Booking\IntegrationEvent\HotelBooking\AccommodationReplaced;

class AccommodationReplacedRegistrar extends AbstractRegistrar
{
    public function register(BookingEventInterface $event): void
    {
        assert($event instanceof AccommodationReplaced);

        $identifier = new ChangesIdentifier(
            $event->bookingId,
            "accommodation[$event->accommodationId]"
        );
        $currentChanges = $this->changesStorage->find($identifier);

        if (!$currentChanges) {
            $currentChanges = Changes::makeUpdated(
                $identifier,
                $this->buildDetailsDescription($event),
                $this->eventPayload($event)
            );
        } else {
            //@todo compare details
        }

        $this->changesStorage->store($currentChanges);
    }

    private function buildDetailsDescription(AccommodationReplaced $event): string
    {
        return "Изменен номер в размещении $event->oldRoomName " . self::DIFF_SEPARATOR . " $event->roomName";
    }

    private function eventPayload($event): array
    {
        return [
            'roomId' => $event->roomId,
            'roomName' => $event->roomName,
            'oldRoomName' => $event->oldRoomName,
        ];
    }
}
