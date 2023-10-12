<?php

namespace Module\Booking\Domain\Booking\Service\HotelBooking\QuotaManager\ProcessingMethod;


use Module\Booking\Domain\Booking\Booking;
use Module\Booking\Domain\Booking\Entity\HotelBooking;
use Module\Booking\Domain\Booking\Service\HotelBooking\QuotaManager\QuotaProcessingMethodInterface;

class Site implements QuotaProcessingMethodInterface
{
    public function process(Booking $booking, HotelBooking $details): void
    {
        // TODO: Implement process() method.
    }
}
