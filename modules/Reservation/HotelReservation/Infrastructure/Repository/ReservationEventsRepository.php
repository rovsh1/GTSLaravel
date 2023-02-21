<?php

namespace Module\Reservation\HotelReservation\Infrastructure\Repository;

use Module\Reservation\Common\Domain\ValueObject\ReservationTypeEnum;
use Module\Reservation\HotelReservation\Domain\Repository\ReservationEventsRepositoryInterface;
use Module\Reservation\HotelReservation\Infrastructure\Models\Event;

class ReservationEventsRepository implements ReservationEventsRepositoryInterface
{
    public function getReservationEvents(int $reservationId, ReservationTypeEnum $reservationType, int $revision = null)
    {
        $q = Event::whereReservation($reservationId, $reservationType->value)
            ->when($revision, fn($q, $v) => $q->whereRevision($v))
            ->orderBy('created_at', 'desc');

        foreach ($q->cursor() as $r) {

        }
    }
}
