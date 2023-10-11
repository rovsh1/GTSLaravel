<?php

namespace Module\Booking\Deprecated\HotelBooking\Service\QuotaManager\ProcessingMethod;

use Module\Booking\Deprecated\HotelBooking\HotelBooking;
use Module\Booking\Deprecated\HotelBooking\Service\QuotaManager\QuotaProcessingMethodInterface;

class Request implements QuotaProcessingMethodInterface
{
    public function process(HotelBooking $booking): void
    {
        //Списание квот не требуется
    }
}
