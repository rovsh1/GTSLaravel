<?php

namespace Pkg\Booking\Requesting\Domain\Service\ChangesRegistrar;

use Pkg\Booking\Requesting\Domain\Entity\Changes;
use Pkg\Booking\Requesting\Domain\ValueObject\ChangesIdentifier;
use Sdk\Booking\IntegrationEvent\BookingEventInterface;
use Sdk\Booking\IntegrationEvent\HotelBooking\AccommodationModified;
use Sdk\Booking\ValueObject\HotelBooking\AccommodationDetails;
use Sdk\Shared\Contracts\Support\CanEquate;

class AccommodationModifiedRegistrar extends AbstractRegistrar
{
    public function register(BookingEventInterface $event): void
    {
        assert($event instanceof AccommodationModified);

        $identifierPrefix = "accommodation[$event->accommodationId]";
        $identifier = ChangesIdentifier::make($event->bookingId, $identifierPrefix);
        $currentChanges = $this->changesStorage->find($identifier);
        $currentDetails = AccommodationDetails::deserialize($event->afterPayload);

        if (!$currentChanges) {
            $currentChanges = Changes::makeUpdated(
                $identifier,
                "Изменено размещение $event->roomName",
                $this->eventPayload($event)
            );
            $originalDetails = AccommodationDetails::deserialize($event->beforePayload);
        } else {
            $originalDetails = AccommodationDetails::deserialize($currentChanges->payload()['original']);
        }
        $this->changesStorage->store($currentChanges);

        $this->registerEarlyCheckIn($event, $originalDetails, $currentDetails);
    }

    private function registerEarlyCheckIn(
        AccommodationModified $event,
        AccommodationDetails $originalDetails,
        AccommodationDetails $afterDetails
    ): void {
        if ($this->isFieldEqual($originalDetails->earlyCheckIn(), $afterDetails->earlyCheckIn())) {
            $this->changesStorage->remove($this->makeIdentifier($event, 'earlyCheckIn'));
        } else {
            $this->changesStorage->store(
                Changes::makeUpdated(
                    $this->makeIdentifier($event, 'earlyCheckIn'),
                    'Изменено время раннего заезда '
                )
            );
        }
    }

    private function makeIdentifier(AccommodationModified $event, string $field): ChangesIdentifier
    {
        return ChangesIdentifier::make($event->bookingId, "accommodation[$event->accommodationId].$field");
    }

    private function eventPayload(AccommodationModified $event): array
    {
        return [
            'roomId' => $event->roomId,
            'roomName' => $event->roomName,
            'original' => $event->beforePayload
        ];
    }

    private function isFieldEqual(mixed $a, mixed $b): bool
    {
        if ($a === $b) {
            return true;
        } elseif ($a instanceof CanEquate) {
            return $a->isEqual($b);
        } elseif ($b instanceof CanEquate) {
            return $b->isEqual($a);
        } else {
            return false;
        }
    }

    private function buildDetailsDescription($event): string
    {
        return '';
    }
}
