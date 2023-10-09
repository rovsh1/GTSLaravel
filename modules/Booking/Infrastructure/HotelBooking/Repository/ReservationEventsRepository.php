<?php

namespace Module\Booking\Infrastructure\HotelBooking\Repository;

use Module\Booking\Deprecated\HotelBooking\Repository\ReservationEventsRepositoryInterface;
use Module\Booking\Domain\Shared\ValueObject\BookingTypeEnum;
use Module\Booking\Infrastructure\HotelBooking\Models\Event;

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
