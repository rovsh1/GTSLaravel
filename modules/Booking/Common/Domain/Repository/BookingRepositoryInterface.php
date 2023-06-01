<?php

namespace Module\Booking\Common\Domain\Repository;

use Carbon\CarbonInterface;
use Module\Booking\Common\Domain\Entity\Booking;

interface BookingRepositoryInterface
{
    public function find(int $id): ?Booking;

    public function update(Booking $booking): bool;

    /**
     * @param CarbonInterface $dateUpdate
     * @param int|null $hotelId
     * @return Booking[]
     */
    public function searchByDateUpdate(CarbonInterface $dateUpdate, ?int $hotelId): array;

    /**
     * @param int|null $hotelId
     * @return Booking[]
     */
    public function searchActive(?int $hotelId): array;
}
