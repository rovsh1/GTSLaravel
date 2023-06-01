<?php

namespace Module\Booking\Hotel\Domain\Repository;

use Carbon\CarbonInterface;
use Module\Booking\Hotel\Domain\Entity\Booking;

interface BookingRepositoryInterface
{
    public function find(int $id): ?Booking;

    /**
     * @param CarbonInterface $dateUpdate
     * @param int|null $hotelId
     * @return Reservation[]
     */
    public function searchByDateUpdate(CarbonInterface $dateUpdate, ?int $hotelId): array;

    /**
     * @param int|null $hotelId
     * @return Reservation[]
     */
    public function searchActive(?int $hotelId): array;

    public function update(Booking $booking): bool;
}
