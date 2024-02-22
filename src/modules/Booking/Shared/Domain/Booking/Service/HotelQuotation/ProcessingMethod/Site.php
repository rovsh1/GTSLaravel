<?php

namespace Module\Booking\Shared\Domain\Booking\Service\HotelQuotation\ProcessingMethod;


use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Service\HotelQuotation\QuotaProcessingMethodInterface;
use Sdk\Booking\Entity\Details\HotelBooking;

class Site implements QuotaProcessingMethodInterface
{
    public function process(Booking $booking, HotelBooking $details): void
    {
        // TODO: Implement process() method.
    }
}
