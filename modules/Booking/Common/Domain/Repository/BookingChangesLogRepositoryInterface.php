<?php

declare(strict_types=1);

namespace Module\Booking\Common\Domain\Repository;

use Illuminate\Database\Eloquent\Collection;
use Module\Booking\Common\Domain\Event\BookingEventInterface;
use Module\Booking\Common\Infrastructure\Models\BookingChangesLog;

interface BookingChangesLogRepositoryInterface
{
    public function logBookingChange(BookingEventInterface $event, array $context = []): void;

    /**
     * @param int $bookingId
     * @return Collection<int, BookingChangesLog>
     */
    public function getStatusHistory(int $bookingId): Collection;
}
