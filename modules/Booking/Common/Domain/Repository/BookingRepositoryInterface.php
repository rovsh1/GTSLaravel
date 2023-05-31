<?php

namespace Module\Booking\Common\Domain\Repository;

use Module\Booking\Common\Domain\Entity\Booking;

interface BookingRepositoryInterface
{
    public function find(int $id): ?Booking;

    public function update(Booking $booking): bool;
}
