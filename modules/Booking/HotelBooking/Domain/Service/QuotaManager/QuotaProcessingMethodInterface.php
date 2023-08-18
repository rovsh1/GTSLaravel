<?php

namespace Module\Booking\HotelBooking\Domain\Service\QuotaManager;

use Module\Booking\HotelBooking\Domain\Entity\Booking;

interface QuotaProcessingMethodInterface
{
    public function process(Booking $booking): void;
}
