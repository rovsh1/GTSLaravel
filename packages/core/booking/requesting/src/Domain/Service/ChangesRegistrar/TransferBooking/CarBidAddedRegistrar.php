<?php

namespace Pkg\Booking\Requesting\Domain\Service\ChangesRegistrar\TransferBooking;

use Pkg\Booking\Requesting\Domain\Entity\Changes;
use Pkg\Booking\Requesting\Domain\Service\ChangesRegistrar\AbstractRegistrar;
use Pkg\Booking\Requesting\Domain\ValueObject\ChangesIdentifier;
use Sdk\Booking\IntegrationEvent\BookingEventInterface;
use Sdk\Booking\IntegrationEvent\TransferBooking\CarBidAdded;

class CarBidAddedRegistrar extends AbstractRegistrar
{
    public function register(BookingEventInterface $event): void
    {
        assert($event instanceof CarBidAdded);

        \Log::debug('CarBidAdded', ['e' => $event->carBidId, 'name' => $event->carName]);
        $identifier = new ChangesIdentifier($event->bookingId, "carBid[$event->carBidId]");
        $currentChanges = $this->changesStorage->find($identifier);

        if (!$currentChanges) {
            $currentChanges = Changes::makeCreated(
                $identifier,
                "Добавлен автомобиль \"$event->carName\"",
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
            'carId' => $event->carId,
            'carName' => $event->carName,
        ];
    }
}
