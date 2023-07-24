<?php

namespace Module\Booking\HotelBooking\Infrastructure\Repository;

use Module\Booking\Common\Domain\ValueObject\BookingTypeEnum;
use Module\Booking\HotelBooking\Domain\Repository\ReservationEventsRepositoryInterface;
use Module\Booking\HotelBooking\Infrastructure\Models\Event;

class ReservationEventsRepository implements ReservationEventsRepositoryInterface
{
    public function getReservationEvents(int $reservationId, BookingTypeEnum $reservationType, int $revision = null)
    {
        $q = Event::whereReservation($reservationId, $reservationType->value)
            ->when($revision, fn($q, $v) => $q->whereRevision($v))
            ->orderBy('created_at', 'desc');

        foreach ($q->cursor() as $r) {

        }
    }
}
