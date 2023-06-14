<?php

namespace Module\Booking\Common\Domain\Repository;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Collection;
use Module\Booking\Common\Domain\Entity\BookingInterface;

interface BookingRepositoryInterface
{
    public function find(int $id): ?BookingInterface;

    public function store(BookingInterface $booking): bool;

    public function get(): Collection;

    /**
     * @param CarbonInterface $dateUpdate
     * @param int|null $hotelId
     * @return BookingInterface[]
     */
    public function searchByDateUpdate(CarbonInterface $dateUpdate, ?int $hotelId): array;

    /**
     * @param int|null $hotelId
     * @return BookingInterface[]
     */
    public function searchActive(?int $hotelId): array;
}
