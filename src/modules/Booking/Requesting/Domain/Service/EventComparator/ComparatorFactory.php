<?php

namespace Module\Booking\Requesting\Domain\Service\EventComparator;

use Sdk\Booking\IntegrationEvent\BookingEventInterface;

class ComparatorFactory
{
    public function build(BookingEventInterface $event): ?ComparatorInterface
    {
        return null;
//        return match ($event) {
//            PeriodChanged::class => 1
//        };
    }
}