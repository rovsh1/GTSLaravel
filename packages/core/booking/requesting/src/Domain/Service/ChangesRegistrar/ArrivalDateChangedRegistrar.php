<?php

namespace Pkg\Booking\Requesting\Domain\Service\ChangesRegistrar;

use Pkg\Booking\Requesting\Domain\Entity\Changes;
use Pkg\Booking\Requesting\Domain\ValueObject\ChangesIdentifier;
use Sdk\Booking\IntegrationEvent\ArrivalDateChanged;
use Sdk\Booking\IntegrationEvent\BookingEventInterface;

class ArrivalDateChangedRegistrar extends AbstractRegistrar
{
    public function register(BookingEventInterface $event): void
    {
        assert($event instanceof ArrivalDateChanged);

        $identifier = new ChangesIdentifier($event->bookingId, 'date');
        $currentChanges = $this->changesStorage->find($identifier);

        if ($currentChanges) {
            $currentChanges->setDescription('Дата прибытия изменена');
        } else {
            $currentChanges = Changes::makeUpdated($identifier, 'Дата прибытия изменена');
        }

        $this->changesStorage->store($currentChanges);
    }
}
