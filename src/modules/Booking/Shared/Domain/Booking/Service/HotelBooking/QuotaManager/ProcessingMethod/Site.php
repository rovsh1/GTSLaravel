<?php

namespace Module\Booking\Shared\Domain\Booking\Service\HotelBooking\QuotaManager\ProcessingMethod;


use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Entity\HotelBooking;
use Module\Booking\Shared\Domain\Booking\Service\HotelBooking\QuotaManager\QuotaProcessingMethodInterface;

class Site implements QuotaProcessingMethodInterface
{
    public function process(Booking $booking, HotelBooking $details): void
    {
        // TODO: Implement process() method.
    }
}
