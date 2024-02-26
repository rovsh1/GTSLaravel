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

        if (!$currentChanges) {
            $currentChanges = Changes::makeUpdated(
                $identifier,
                "Изменено размещение $event->roomName",
                $this->eventPayload($event)
            );
        }
        $this->changesStorage->store($currentChanges);

        $this->registerFieldChanges($event, 'earlyCheckIn', 'Изменено время раннего заезда');
        $this->registerFieldChanges($event, 'lateCheckOut', 'Изменено время позднего выезда');
        $this->registerFieldChanges($event, 'guestNote', 'Изменен комментарий гостя');
        $this->registerFieldChanges($event, 'rateId', 'Изменен тариф');
    }

    private function registerFieldChanges(AccommodationModified $event, string $field, string $description): void
    {
        $identifier = $this->makeIdentifier($event, $field);
        $currentChanges = $this->changesStorage->find($identifier);

        $originalDetails = $currentChanges !== null ? AccommodationDetails::deserialize($currentChanges->payload()['original']) : null;
        $afterDetails = AccommodationDetails::deserialize($event->afterPayload);
        if ($this->isFieldEqual($originalDetails?->$field(), $afterDetails->$field())) {
            $this->changesStorage->remove($this->makeIdentifier($event, $field));
        } else {
            $this->changesStorage->store(
                Changes::makeUpdated(
                    $this->makeIdentifier($event, $field),
                    $description,
                    $this->eventPayload($event)
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
        if ($a instanceof CanEquate) {
            return $a->isEqual($b);
        } elseif ($a === $b) {
            return true;
        } else {
            return false;
        }
    }

    private function buildDetailsDescription($event): string
    {
        return '';
    }
}
