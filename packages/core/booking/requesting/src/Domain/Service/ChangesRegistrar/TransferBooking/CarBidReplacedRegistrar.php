<?php

namespace Pkg\Booking\Requesting\Domain\Service\ChangesRegistrar\TransferBooking;

use Pkg\Booking\Requesting\Domain\Entity\Changes;
use Pkg\Booking\Requesting\Domain\Service\ChangesRegistrar\AbstractRegistrar;
use Pkg\Booking\Requesting\Domain\ValueObject\ChangesIdentifier;
use Sdk\Booking\IntegrationEvent\BookingEventInterface;
use Sdk\Booking\IntegrationEvent\TransferBooking\CarBidReplaced;

class CarBidReplacedRegistrar extends AbstractRegistrar
{
    public function register(BookingEventInterface $event): void
    {
        assert($event instanceof CarBidReplaced);

        $identifier = new ChangesIdentifier($event->bookingId, "carBid[$event->carBidId]");
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

    private function buildDetailsDescription(CarBidReplaced $event): string
    {
        return "Изменен автомобиль $event->oldCarName " . self::DIFF_SEPARATOR . " $event->carName";
    }

    private function eventPayload($event): array
    {
        return [
            'carId' => $event->carId,
            'carName' => $event->carName,
            'oldCarName' => $event->oldCarName,
        ];
    }
}
