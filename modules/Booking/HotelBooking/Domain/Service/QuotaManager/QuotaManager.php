<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Domain\Service\QuotaManager;

use Module\Booking\HotelBooking\Domain\Entity\Booking;

class QuotaManager
{
    public function __construct(
        private readonly QuotaProcessingMethodFactory $quotaProcessingMethodFactory
    ) {}

    /**
     * @param Booking $booking
     * @return void
     */
    public function process(Booking $booking): void
    {
        $quotaProcessingMethod = $this->quotaProcessingMethodFactory->build($booking->quotaProcessingMethod());
        $quotaProcessingMethod->process($booking);
    }
}
