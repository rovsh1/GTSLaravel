<?php

namespace Module\Booking\HotelBooking\Domain\Service\QuotaManager;

use Module\Booking\Common\Domain\ValueObject\BookingId;
use Module\Booking\HotelBooking\Domain\Entity\Booking;
use Module\Booking\HotelBooking\Domain\Service\QuotaManager\Exception\ClosedRoomDateQuota;
use Module\Booking\HotelBooking\Domain\Service\QuotaManager\Exception\NotEnoughRoomDateQuota;
use Module\Booking\HotelBooking\Domain\Service\QuotaManager\Exception\NotFoundRoomDateQuota;

interface QuotaProcessingMethodInterface
{
    public function process(BookingId $bookingId): void;

    /**
     * @param Booking $booking
     * @param int $roomId
     * @return void
     * @throws ClosedRoomDateQuota
     * @throws NotEnoughRoomDateQuota
     * @throws NotFoundRoomDateQuota
     */
    public function ensureRoomAvailable(Booking $booking, int $roomId): void;
}
