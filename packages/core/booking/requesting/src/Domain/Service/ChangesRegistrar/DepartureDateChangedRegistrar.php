<?php

namespace Pkg\Booking\Requesting\Domain\Service\ChangesRegistrar;

use Pkg\Booking\Requesting\Domain\Entity\Changes;
use Pkg\Booking\Requesting\Domain\ValueObject\ChangesIdentifier;
use Sdk\Booking\IntegrationEvent\BookingEventInterface;
use Sdk\Booking\IntegrationEvent\DepartureDateChanged;

class DepartureDateChangedRegistrar extends AbstractRegistrar
{
    public function register(BookingEventInterface $event): void
    {
        assert($event instanceof DepartureDateChanged);

        $identifier = new ChangesIdentifier($event->bookingId, 'date');
        $currentChanges = $this->changesStorage->find($identifier);

        if ($currentChanges) {
            $currentChanges->setDescription('Дата отправления изменена');
        } else {
            $currentChanges = Changes::makeUpdated($identifier, 'Дата отправления изменена');
        }

        $this->changesStorage->store($currentChanges);
    }
}
