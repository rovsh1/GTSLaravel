<?php

namespace Module\Booking\HotelBooking\Domain\Service\QuotaManager;

use Module\Booking\HotelBooking\Domain\Entity\Booking;

class ByRequest implements QuotaMethodInterface
{
    public function process(Booking $booking): void
    {
        //Списание квот не требуется
    }
}
