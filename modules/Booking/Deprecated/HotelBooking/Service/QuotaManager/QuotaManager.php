<?php

declare(strict_types=1);

namespace Module\Booking\Deprecated\HotelBooking\Service\QuotaManager;

use Module\Booking\Deprecated\HotelBooking\HotelBooking;
use Module\Booking\Deprecated\HotelBooking\Service\QuotaManager\Exception\ClosedRoomDateQuota;
use Module\Booking\Deprecated\HotelBooking\Service\QuotaManager\Exception\NotEnoughRoomDateQuota;
use Module\Booking\Deprecated\HotelBooking\Service\QuotaManager\Exception\NotFoundRoomDateQuota;

class QuotaManager
{
    public function __construct(
        private readonly QuotaProcessingMethodFactory $quotaProcessingMethodFactory
    ) {}

    /**
     * @param HotelBooking $booking
     * @return void
     * @throws NotFoundRoomDateQuota
     * @throws ClosedRoomDateQuota
     * @throws NotEnoughRoomDateQuota
     */
    public function process(HotelBooking $booking): void
    {
        $quotaProcessingMethod = $this->quotaProcessingMethodFactory->build($booking->quotaProcessingMethod());
        $quotaProcessingMethod->process($booking);
    }
}
