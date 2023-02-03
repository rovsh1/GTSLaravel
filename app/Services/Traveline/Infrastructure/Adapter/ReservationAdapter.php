<?php

namespace GTS\Services\Traveline\Infrastructure\Adapter;

use Carbon\CarbonInterface;
use GTS\Services\Traveline\Domain\Adapter\ReservationAdapterInterface;

class ReservationAdapter implements ReservationAdapterInterface
{

    public function getReservations(?int $id = null, ?int $hotelId = null, ?CarbonInterface $startDate = null)
    {
        // TODO: Implement getReservations() method.
    }

    public function confirmReservation(int $id): void
    {
        // TODO: Implement confirmReservation() method.
    }
}
