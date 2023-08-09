<?php

namespace Module\Booking\HotelBooking\Domain\Service\QuotaManager;

use Illuminate\Support\Collection;
use Module\Booking\Common\Domain\Service\StatusRules\AdministratorRules;
use Module\Booking\HotelBooking\Domain\Entity\Booking;
use Module\Booking\HotelBooking\Domain\Entity\RoomBooking;

class ByQuota implements QuotaMethodInterface
{
    public function __construct(
        private readonly AdministratorRules $administratorRules,
    ) {}

    public function process(Booking $booking): void
    {
        if ($this->isEditableBooking($booking)) {
            //@todo зарезервировать квоты
        } elseif ($this->isBookingConfirmed($booking)) {
            //@todo списать квоты
            //@todo вернуть резерв
        } elseif ($this->isBookingCancelled($booking)) {
            //@todo удалить все записи от брони
            //reset
        }
    }

    private function countRooms(Booking $booking): mixed
    {
        $roomsCountByHotelRoomId = $booking->roomBookings()
            ->groupBy(fn(RoomBooking $roomBooking) => $roomBooking->roomInfo()->id())
            ->map(fn(Collection $rooms) => $rooms->count());

        return $roomsCountByHotelRoomId;
    }

    private function isEditableBooking(Booking $booking): bool
    {
        return $this->administratorRules->isEditableStatus($booking->status());
    }

    private function isBookingConfirmed(Booking $booking): bool
    {
        return false;
    }

    private function isBookingCancelled(Booking $booking): bool
    {
        //@todo добавить условние если бронь удалена
        return $this->administratorRules->isCancelledStatus($booking->status());
    }
}
