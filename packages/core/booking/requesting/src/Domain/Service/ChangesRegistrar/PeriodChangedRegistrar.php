<?php

namespace Pkg\Booking\Requesting\Domain\Service\ChangesRegistrar;

use Pkg\Booking\Requesting\Domain\Entity\Changes;
use Pkg\Booking\Requesting\Domain\ValueObject\ChangesIdentifier;
use Sdk\Booking\IntegrationEvent\BookingEventInterface;
use Sdk\Booking\IntegrationEvent\HotelBooking\PeriodChanged;

class PeriodChangedRegistrar extends AbstractRegistrar
{
    public function register(BookingEventInterface $event): void
    {
        assert($event instanceof PeriodChanged);

        $identifier = new ChangesIdentifier($event->bookingId, 'period');
        $currentChanges = $this->changesStorage->find($identifier);

        if ($currentChanges) {
            $currentChanges->setDescription(
                $this->buildDescription($currentChanges->payload(), $event->after->toArray())
            );
        } else {
            $currentChanges = Changes::makeUpdated(
                $identifier,
                $this->buildDescription($event->before->toArray(), $event->after->toArray()),
                $event->before->toArray(),
            );
        }

        $this->changesStorage->store($currentChanges);
    }

    private function buildDescription(array $periodBefore, array $periodAfter): string
    {
        return "Период изменен {$this->periodPresentation($periodBefore)} "
            . self::DIFF_SEPARATOR
            . "  {$this->periodPresentation($periodAfter)}";
    }

    private function periodPresentation(array $payload): string
    {
        return "[{$this->datePresentation($payload['dateFrom'])} - {$this->datePresentation($payload['dateTo'])}]";
    }

    private function datePresentation(\DateTimeInterface $date): string
    {
        return $date->format('Y-m-d');
    }
}