<?php

namespace Module\Booking\Domain\Shared\Repository;

use Illuminate\Database\Eloquent\Builder;
use Module\Booking\Domain\Shared\Entity\BookingInterface;

interface BookingRepositoryInterface
{
    public function find(int $id): ?BookingInterface;

    public function query(): Builder;

    public function delete(BookingInterface $booking): void;

    /**
     * @param int[] $ids
     * @return void
     */
    public function bulkDelete(array $ids): void;
}
