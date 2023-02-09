<?php

namespace GTS\Integration\Traveline\Infrastructure\Adapter;

use Carbon\CarbonInterface;
use GTS\Integration\Traveline\Domain\Adapter\ReservationAdapterInterface;
use GTS\Shared\Infrastructure\Adapter\AbstractPortAdapter;

class ReservationAdapter extends AbstractPortAdapter implements ReservationAdapterInterface
{
    public function confirmReservation(int $id): void
    {
        // TODO: Implement confirmReservation() method.
    }

    public function getActiveReservations(): array
    {
        return [];
    }

    public function getReservationById(int $id): mixed
    {
        return $this->request('hotelReservation/findById', ['id' => $id]);
    }

    public function getReservationsByHotelId(int $hotelId): array
    {
        return [];
    }

    public function getUpdatedReservations(CarbonInterface $startDate = null, ?int $hotelId = null): array
    {
        return [];
    }
}
