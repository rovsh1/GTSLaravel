<?php

namespace GTS\Reservation\Infrastructure\Repository;

use GTS\Reservation\Domain\Repository\ReservationEventsRepositoryInterface;
use GTS\Reservation\Domain\ValueObject\ReservationTypeEnum;
use GTS\Reservation\Infrastructure\Models\ReservationEvent;

class ReservationEventsRepository implements ReservationEventsRepositoryInterface
{
    public function getReservationEvents(int $reservationId, ReservationTypeEnum $reservationType, int $revision = null)
    {
        $q = ReservationEvent::whereReservation($reservationId, $reservationType->value)
            ->when($revision, fn($q, $v) => $q->whereRevision($v))
            ->orderBy('created_at', 'desc');

        foreach ($q->cursor() as $r) {

        }
    }
}
