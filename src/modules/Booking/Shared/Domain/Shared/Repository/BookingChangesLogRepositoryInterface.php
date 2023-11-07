<?php

declare(strict_types=1);

namespace Module\Booking\Shared\Domain\Shared\Repository;

use Illuminate\Database\Eloquent\Collection;
use Module\Booking\Shared\Domain\Shared\Event\BookingEventInterface;
use Module\Booking\Shared\Infrastructure\Models\BookingChangesLog;

interface BookingChangesLogRepositoryInterface
{
    public function logBookingChange(BookingEventInterface $event, array $context = []): void;

    /**
     * @param int $bookingId
     * @return Collection<int, BookingChangesLog>
     */
    public function getStatusHistory(int $bookingId): Collection;
}
