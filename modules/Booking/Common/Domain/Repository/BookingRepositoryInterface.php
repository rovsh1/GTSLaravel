<?php

namespace Module\Booking\Common\Domain\Repository;

use Carbon\CarbonInterface;
use Module\Booking\Common\Domain\Entity\BookingInterface;

interface BookingRepositoryInterface
{
    public function find(int $id): ?BookingInterface;

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
