<?php

namespace Module\Booking\Requesting\Domain\Service\EventDescriptor;

use Module\Booking\Requesting\Domain\Service\EventComparator\ChangesDto;
use Module\Booking\Requesting\Domain\Service\EventComparator\ComparatorInterface;
use Sdk\Booking\IntegrationEvent\BookingEventInterface;

class AccommodationAddedComparator implements ComparatorInterface
{
    public function get(BookingEventInterface $event): ChangesDto
    {
        // TODO: Implement get() method.
    }
}