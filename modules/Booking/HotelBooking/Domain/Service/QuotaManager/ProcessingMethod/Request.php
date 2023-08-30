<?php

namespace Module\Booking\HotelBooking\Domain\Service\QuotaManager\ProcessingMethod;

use Module\Booking\Common\Domain\ValueObject\BookingId;
use Module\Booking\HotelBooking\Domain\Entity\Booking;
use Module\Booking\HotelBooking\Domain\Service\QuotaManager\QuotaProcessingMethodInterface;

class Request implements QuotaProcessingMethodInterface
{
    public function process(Booking $booking): void
    {
        //Списание квот не требуется
    }
}
