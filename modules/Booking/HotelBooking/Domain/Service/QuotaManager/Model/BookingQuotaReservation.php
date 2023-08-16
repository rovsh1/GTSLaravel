<?php

declare(strict_types=1);

namespace Module\Booking\HotelBooking\Domain\Service\QuotaManager\Model;

use Module\Booking\HotelBooking\Domain\Entity\Booking;
use Module\Booking\HotelBooking\Domain\Entity\RoomBooking;

class BookingQuotaReservation
{
    /** @var int[] $roomIds */
    private array $roomIds;

    public function __construct(
        private readonly Booking $booking
    ) {
        $this->roomIds = $this->booking->roomBookings()->map(
            fn(RoomBooking $roomBooking) => $roomBooking->roomInfo()->id()
        )->all();
    }

    public function addRoom(int $roomId): void
    {
        $this->roomIds[] = $roomId;
    }

    public function getRoomIds(): array
    {
        return array_unique($this->roomIds);
    }

    /**
     * @param Booking $booking
     * @return array<int, RoomDateQuotaReservation>
     */
    public function getRoomQuotaReservations(): array
    {
        $roomsCount = [];
        foreach ($this->roomIds as $hotelRoomId) {
            if (!array_key_exists($hotelRoomId, $roomsCount)) {
                $roomsCount[$hotelRoomId] = 1;
            } else {
                $roomsCount[$hotelRoomId]++;
            }
        }

        $roomsCountByDate = [];
        foreach ($this->booking->period()->includedDates() as $date) {
            foreach ($roomsCount as $roomId => $count) {
                $roomsCountByDate[] = new RoomDateQuotaReservation(
                    roomId: $roomId,
                    date: $date,
                    count: $count
                );
            }
        }

        return $roomsCountByDate;
    }
}
