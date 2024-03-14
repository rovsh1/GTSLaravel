<?php

namespace Pkg\Booking\Requesting\Domain\Service\ChangesRegistrar\TransferBooking;

use Pkg\Booking\Requesting\Domain\Entity\Changes;
use Pkg\Booking\Requesting\Domain\Service\ChangesRegistrar\AbstractRegistrar;
use Pkg\Booking\Requesting\Domain\ValueObject\ChangesIdentifier;
use Sdk\Booking\IntegrationEvent\BookingEventInterface;
use Sdk\Booking\IntegrationEvent\TransferBooking\CarBidModified;
use Sdk\Booking\ValueObject\CarBid\CarBidDetails;
use Sdk\Shared\Contracts\Support\CanEquate;

class CarBidModifiedRegistrar extends AbstractRegistrar
{
    public function register(BookingEventInterface $event): void
    {
        assert($event instanceof CarBidModified);

        $this->registerFieldChanges($event, 'passengersCount', 'Изменено количество пассажиров');
        $this->registerFieldChanges($event, 'baggageCount', 'Изменено количество багажа');
        $this->registerFieldChanges($event, 'carsCount', 'Изменено количество автомобилей');
    }

    private function registerFieldChanges(CarBidModified $event, string $field, string $description): void
    {
        $isFieldModified = $this->isFieldModified($event->beforePayload, $event->afterPayload, $field);
        if (!$isFieldModified) {
            return;
        }

        $identifier = $this->makeIdentifier($event, $field);
        $currentChanges = $this->changesStorage->find($identifier);
        $originalDetails = $currentChanges !== null ? CarBidDetails::deserialize(
            $currentChanges->payload()['original']
        ) : null;

        if ($originalDetails === null) {
            $this->changesStorage->store(
                Changes::makeUpdated(
                    $this->makeIdentifier($event, $field),
                    $description,
                    $this->eventPayload($event)
                )
            );

            return;
        }

        $afterDetails = CarBidDetails::deserialize($event->afterPayload);
        if ($this->isFieldValueEqual($originalDetails->$field(), $afterDetails->$field())) {
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

    private function makeIdentifier(CarBidModified $event, string $field): ChangesIdentifier
    {
        return ChangesIdentifier::make($event->bookingId, "carBid[$event->carBidId].$field");
    }

    private function eventPayload(CarBidModified $event): array
    {
        return [
            'carId' => $event->carId,
            'carName' => $event->carName,
            'original' => $event->beforePayload
        ];
    }

    private function isFieldModified(array $detailsBefore, array $detailsAfter, string $field): bool
    {
        $beforeValue = $detailsBefore[$field] ?? null;
        $afterValue = $detailsAfter[$field] ?? null;

        return $beforeValue !== $afterValue;
    }

    private function isFieldValueEqual(mixed $a, mixed $b): bool
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
