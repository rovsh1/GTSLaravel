<?php

namespace Module\Booking\Requesting\Domain\Service\EventComparator;

use Sdk\Booking\IntegrationEvent\BookingEventInterface;

interface ComparatorInterface
{
    public function get(BookingEventInterface $event): ChangesDto;
}