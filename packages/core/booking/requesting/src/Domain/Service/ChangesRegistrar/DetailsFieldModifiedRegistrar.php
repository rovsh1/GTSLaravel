<?php

namespace Pkg\Booking\Requesting\Domain\Service\ChangesRegistrar;

use Pkg\Booking\Requesting\Domain\Entity\Changes;
use Pkg\Booking\Requesting\Domain\ValueObject\ChangesIdentifier;
use Sdk\Booking\IntegrationEvent\BookingEventInterface;
use Sdk\Booking\IntegrationEvent\DetailsFieldModified;

class DetailsFieldModifiedRegistrar extends AbstractRegistrar
{
    public function register(BookingEventInterface $event): void
    {
        assert($event instanceof DetailsFieldModified);

        $identifier = new ChangesIdentifier($event->bookingId, $event->field);
        $currentChanges = $this->changesStorage->find($identifier);

        if ($currentChanges) {
            $currentChanges->setDescription($this->buildDescription($event));
        } else {
            $currentChanges = Changes::makeUpdated($identifier, $this->buildDescription($event));
        }

        $this->changesStorage->store($currentChanges);
    }

    private function buildDescription(DetailsFieldModified $event): string
    {
        return "Изменены детали бронирования $event->valueBefore " . self::DIFF_SEPARATOR . " $event->value";
    }
}
