<?php

namespace Module\Booking\Domain\Shared\Repository;

use Illuminate\Database\Eloquent\Builder;
use Module\Booking\Domain\ServiceBooking\ServiceBooking;
use Module\Booking\Domain\Shared\Entity\BookingInterface;

interface BookingRepositoryInterface
{
    public function find(int $id): ?ServiceBooking;

    public function query(): Builder;

    public function delete(BookingInterface|ServiceBooking $booking): void;

    /**
     * @param int[] $ids
     * @return void
     */
    public function bulkDelete(array $ids): void;
}
