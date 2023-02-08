<?php

namespace GTS\Integration\Traveline\Infrastructure\Adapter;

use Carbon\CarbonInterface;

use GTS\Integration\Traveline\Domain\Adapter\ReservationAdapterInterface;
use GTS\Shared\Infrastructure\Adapter\AbstractPortAdapter;

class ReservationAdapter extends AbstractPortAdapter implements ReservationAdapterInterface
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
