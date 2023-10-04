<?php

namespace Module\Booking\Domain\HotelBooking\Service\QuotaManager\ProcessingMethod;

use Module\Booking\Domain\HotelBooking\HotelBooking;
use Module\Booking\Domain\HotelBooking\Service\QuotaManager\QuotaProcessingMethodInterface;

class Request implements QuotaProcessingMethodInterface
{
    public function process(HotelBooking $booking): void
    {
        //Списание квот не требуется
    }
}
