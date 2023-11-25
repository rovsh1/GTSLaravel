<?php

namespace Module\Booking\Moderation\Domain\Booking\Service\HotelBooking\QuotaManager\ProcessingMethod;


use Module\Booking\Moderation\Domain\Booking\Service\HotelBooking\QuotaManager\QuotaProcessingMethodInterface;
use Module\Booking\Shared\Domain\Booking\Booking;
use Sdk\Booking\Entity\BookingDetails\HotelBooking;

class Site implements QuotaProcessingMethodInterface
{
    public function process(Booking $booking, HotelBooking $details): void
    {
        // TODO: Implement process() method.
    }
}
