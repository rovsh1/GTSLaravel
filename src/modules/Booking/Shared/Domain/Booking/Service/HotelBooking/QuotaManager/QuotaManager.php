<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Booking\Service\HotelBooking\QuotaManager;

use Module\Booking\Shared\Domain\Booking\Booking;
use Module\Booking\Shared\Domain\Booking\Entity\HotelBooking;
use Module\Booking\Shared\Domain\Booking\Service\HotelBooking\QuotaManager\Exception\ClosedRoomDateQuota;
use Module\Booking\Shared\Domain\Booking\Service\HotelBooking\QuotaManager\Exception\NotEnoughRoomDateQuota;
use Module\Booking\Shared\Domain\Booking\Service\HotelBooking\QuotaManager\Exception\NotFoundRoomDateQuota;

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
    public function process(Booking $booking, HotelBooking $details): void
    {
        $quotaProcessingMethod = $this->quotaProcessingMethodFactory->build($details->quotaProcessingMethod());
        $quotaProcessingMethod->process($booking, $details);
    }
}
