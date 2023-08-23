<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Domain\Service\QuotaManager;

use Module\Booking\HotelBooking\Domain\Entity\Booking;
use Module\Booking\HotelBooking\Domain\Service\QuotaManager\Exception\ClosedRoomDateQuota;
use Module\Booking\HotelBooking\Domain\Service\QuotaManager\Exception\NotEnoughRoomDateQuota;
use Module\Booking\HotelBooking\Domain\Service\QuotaManager\Exception\NotFoundRoomDateQuota;

class QuotaManager
{
    public function __construct(
        private readonly QuotaProcessingMethodFactory $quotaProcessingMethodFactory
    ) {}

    /**
     * @param Booking $booking
     * @return void
     * @throws NotFoundRoomDateQuota
     * @throws ClosedRoomDateQuota
     * @throws NotEnoughRoomDateQuota
     */
    public function process(Booking $booking): void
    {
        $quotaProcessingMethod = $this->quotaProcessingMethodFactory->build($booking->quotaProcessingMethod());
        $quotaProcessingMethod->process($booking);
    }
}
